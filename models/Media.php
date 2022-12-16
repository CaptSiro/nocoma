<?php

  require_once __DIR__ . "/../lib/newgen/newgen.php";
  require_once __DIR__ . "/../lib/modelist/modelist.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/User.php";
  require_once __DIR__ . "/Count.php";

  class Media extends StrictModel {
    protected $src, $extension, $basename, $usersID, $hash, $timeCreated;
    const ALL_COLUMNS = ["src", "extension", "basename", "usersID", "hash", "timeCreated"];
    
    protected static function getNumberProps (): array { return []; }
    protected static function getBooleanProps (): array { return []; }
    
    
    
    public static function accept (RequestFile $file, User $user): Result {
      if ($file->error !== UPLOAD_ERR_OK) {
        return fail(new TypeExc("Error when uploading file: '$file->fullName'. Code: '$file->error'"));
      }
      
      $sourceResult = Generate::valid(
        Generate::string(Generate::CHARSET_URL, 10),
        self::isSRCValid()
      );
      
      if ($sourceResult->isFailure()) {
        return $sourceResult;
      }
  
      $footprint = sha1_file($file->temporaryName);
      if (!self::isFileUnique($footprint, $user)) {
        return fail(new NotUniqueValueExc("This file already exists on the server."));
      }
      
      $source = $sourceResult->getSuccess();
      $file->moveTo(HOSTS_DIR . "/$user->website/media/$source" . ((($file->ext ?? "!")[0] === ".") ? $file->ext : ""));
      
      return success(Database::get()->statement(
        "INSERT INTO `media` (`src`, `extension`, `basename`, `usersID`, `hash`)
        VALUE (:src, :ext, :basename, :userID, :hash)",
        [
          new DatabaseParam("src", $source, PDO::PARAM_STR),
          new DatabaseParam("ext", $file->ext, PDO::PARAM_STR),
          new DatabaseParam("basename", $file->name, PDO::PARAM_STR),
          new DatabaseParam("userID", $user->ID),
          new DatabaseParam("hash", $footprint, PDO::PARAM_STR),
        ]
      ));
    }
    public static function throwout (string $source, User $user): Result {
      $fileResult = self::getBySource($source);
      if ($fileResult->isFailure()) {
        return $fileResult;
      }
      
      /** @var Media $file */
      $file = $fileResult->getSuccess();
      $path = HOSTS_DIR . "/$user->website/media/$file->basename$file->extension";
      if (!file_exists($path)) {
        return fail(new NotFoundExc("File does not exist."));
      }
      
      unlink($path);
      return success(Database::get()->statement(
        "DELETE FROM media WHERE src = :src LIMIT 1",
        [new DatabaseParam("src", $source, PDO::PARAM_STR)]
      ));
    }
    
    
    
    public static function getBySource (string $source): Result {
      $post = Database::get()->fetch(
        "SELECT
            " . self::generateSelectColumns("media", self::ALL_COLUMNS) . "
        FROM
          `media`
        WHERE media.src = :src",
        self::class,
        [new DatabaseParam("src", $source, PDO::PARAM_STR)]
      );
  
      if (!isset($post->ID)) {
        return fail(new NotFoundExc("Could not find file by given source."));
      }
  
      return success(self::parseProps($post));
    }
    
    private const SET_SIZE = 20;
    public static function getSet (int $userID, int $offset) {
      return self::parseProps(Database::get()->fetchAll(
        "SELECT
          " . self::generateSelectColumns("media", self::ALL_COLUMNS) . "
        FROM `media`
        WHERE media.usersID = :userID
        ORDER BY timeCreated DESC
        LIMIT :offset, " . self::SET_SIZE,
        self::class,
        [
          new DatabaseParam("offset", $offset * self::SET_SIZE),
          new DatabaseParam("userID", $userID),
        ]
      ));
    }
    
    
    public static function isSRCValid (): Closure {
      return function ($src): Result {
        if ($src == "") {
          return fail(new InvalidArgumentExc("Source is not defined."));
        }
        
        return success(
          Count::parseProps(Database::get()->fetch(
            "SELECT COUNT(*) amount
                  FROM `media`
                  WHERE src = :src",
            Count::class,
            [new DatabaseParam("src", $src, PDO::PARAM_STR)]
          ))->amount != 0
        );
      };
    }
    public static function isFileUnique (string $footprint, User $user): bool {
      return Count::parseProps(Database::get()->fetch(
        "SELECT COUNT(hash) amount
            FROM media
            WHERE usersID = :userID AND hash = :hash",
        Count::class,
        [
          new DatabaseParam("hash", $footprint, PDO::PARAM_STR),
          new DatabaseParam("userID", $user->ID),
        ]
      ))->amount == 0;
    }
  }