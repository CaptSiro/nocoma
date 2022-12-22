<?php

  require_once __DIR__ . "/../lib/newgen/newgen.php";
  require_once __DIR__ . "/../lib/modelist/modelist.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/User.php";
  require_once __DIR__ . "/Count.php";

  class Media extends StrictModel {
    protected $src, $usersID, $basename, $extension, $mimeContentType, $timeCreated, $hash, $size;
    const ALL_COLUMNS = ["src", "usersID", "basename", "extension", "mimeContentType", "timeCreated", "hash", "size"];
    const TABLE_NAME = "`media`";
    
    protected static function getNumberProps (): array { return ["usersID", "size"]; }
    protected static function getBooleanProps (): array { return []; }
    
    
    
    public static function save (RequestFile $file, User $user): Result {
      if ($file->error !== UPLOAD_ERR_OK) {
        return fail(new TypeExc("Error when uploading file: '$file->fullName'. Code: '$file->error'"));
      }
      
      $sourceResult = Generate::valid(
        Generate::string(Generate::CHARSET_URL, 10),
        self::isSRCValid(HOSTS_DIR . "/$user->website/media/")
      );
      
      if ($sourceResult->isFailure()) {
        return $sourceResult;
      }
  
      $footprint = sha1_file($file->temporaryName);
      if (!self::isFileUnique($footprint, $user)) {
        return fail(new NotUniqueValueExc("This file already exists on the server."));
      }
      
      $source = $sourceResult->getSuccess();
      $mimeTypeResult = Response::getMimeType($file->temporaryName);
      if ($mimeTypeResult->isFailure()) {
        return $mimeTypeResult;
      }
      
      $mimeType = $mimeTypeResult->getSuccess();
      $moveResult = $file->moveTo(HOSTS_DIR . "/$user->website/media/$source$file->ext");
      if ($moveResult->isFailure()) {
        return $moveResult;
      }
      
      return success(Database::get()->statement(
        "INSERT INTO `media` (`src`, `extension`, `basename`, `usersID`, `hash`, `size`, `mimeContentType`)
        VALUE (:src, :ext, :basename, :userID, :hash, :size, :mimeType)",
        [
          new DatabaseParam("src", $source, PDO::PARAM_STR),
          new DatabaseParam("ext", $file->ext, PDO::PARAM_STR),
          new DatabaseParam("basename", $file->name, PDO::PARAM_STR),
          new DatabaseParam("userID", $user->ID),
          new DatabaseParam("hash", $footprint, PDO::PARAM_STR),
          new DatabaseParam("size", $file->size),
          new DatabaseParam("mimeType", $mimeType, PDO::PARAM_STR),
        ]
      ));
    }
    public static function delete (string $source, User $user): Result {
      $fileResult = self::getBySource($source);
      if ($fileResult->isFailure()) {
        return $fileResult;
      }
      
      /** @var Media $file */
      $file = $fileResult->getSuccess();
      $path = HOSTS_DIR . "/$user->website/media/$file->src$file->extension";
      if (!file_exists($path)) {
        return fail(new NotFoundExc("File does not exist."));
      }
      
      unlink($path);
      return success(Database::get()->statement(
        "DELETE FROM media WHERE src = :src AND usersID = :userID LIMIT 1",
        [
          new DatabaseParam("src", $source, PDO::PARAM_STR),
          new DatabaseParam("userID", $user->ID)
        ]
      ));
    }
    public static function rename (string $source, string $value): SideEffect {
      return Database::get()->statement(
        "UPDATE `media` SET `basename` = :value WHERE `media`.`src` = :src",
        [
          new DatabaseParam("src", $source, PDO::PARAM_STR),
          new DatabaseParam("value", htmlspecialchars($value), PDO::PARAM_STR),
        ]
      );
    }
    
    
    
    public static function getBySource (string $source): Result {
      $file = Database::get()->fetch(
        "SELECT
            " . self::generateSelectColumns(self::TABLE_NAME, self::ALL_COLUMNS) . "
        FROM
          `media`
        WHERE media.src = :src",
        self::class,
        [new DatabaseParam("src", $source, PDO::PARAM_STR)]
      );
  
      if (!isset($file->src)) {
        return fail(new NotFoundExc("Could not find file by given source."));
      }
  
      return success(self::parseProps($file));
    }
    
    private const SET_SIZE = 20;
  
    const ORDER_BY_DATE_ACCEPTED = "0";
    const ORDER_BY_NAME = "1";
    const ORDER_BY_SIZE = "2";
    
    public static function getSet (int $userID, int $offset, string $orderBy = self::ORDER_BY_DATE_ACCEPTED) {
      switch ($orderBy) {
        case self::ORDER_BY_NAME: {
          $order = "basename ASC, extension ASC";
          break;
        }
        case self::ORDER_BY_SIZE: {
          $order = "size ASC";
          break;
        }
        default: {
          $order = "timeCreated DESC";
          break;
        }
      }
      
      return self::parseProps(Database::get()->fetchAll(
        "SELECT
          " . self::generateSelectColumns(self::TABLE_NAME, self::ALL_COLUMNS) . "
        FROM `media`
        WHERE media.usersID = :userID
        ORDER BY $order
        LIMIT :offset, " . self::SET_SIZE,
        self::class,
        [
          new DatabaseParam("offset", $offset * self::SET_SIZE),
          new DatabaseParam("userID", $userID),
        ]
      ));
    }
    
    
    public static function isSRCValid (string $directory): Closure {
      return function ($src) use ($directory): Result {
        if ($src == "") {
          return fail(new InvalidArgumentExc("Source is not defined."));
        }
  
        $isNotPresentInDatabaseOrInDirectory = Count::parseProps(Database::get()->fetch(
            "SELECT COUNT(*) amount
            FROM " . self::TABLE_NAME . "
            WHERE src = :src",
            Count::class,
            [new DatabaseParam("src", $src, PDO::PARAM_STR)]
          ))->amount != 0 && empty(glob("$directory/$src.*"));
  
        return success($isNotPresentInDatabaseOrInDirectory);
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