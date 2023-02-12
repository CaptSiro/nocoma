<?php
  
  require_once __DIR__ . "/../lib/newgen/newgen.php";
  require_once __DIR__ . "/../lib/modelist/modelist.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/User.php";
  require_once __DIR__ . "/Count.php";
  require_once __DIR__ . "/Media.php";
  
  class ProfilePicture extends StrictModel {
    protected $src, $usersID, $hash, $extension;
    const ALL_COLUMNS = ["src", "usersID", "hash", "extension"];
    const TABLE_NAME = "profilePictures";
    
    protected static function getNumberProps (): array { return ["usersID", "size"]; }
    protected static function getBooleanProps (): array { return []; }
  
    
    
    public static function save (RequestFile $file, User $user): Result {
      if ($file->error !== UPLOAD_ERR_OK) {
        return fail(new TypeExc("Error occurred when uploading file: '$file->fullName'. Code: '$file->error'"));
      }
  
      $hash = sha1_file($file->temporaryName);
      if (self::exists($hash, $user->ID)) {
        return fail(new InvalidArgumentExc("File already exists on the server."));
      }
  
      $sourceResult = Generate::valid(
        Generate::string(Generate::CHARSET_URL, Media::SRC_LENGTH_LASTING),
        self::isSRCValid(HOSTS_DIR . "/$user->website/media/")
      );
      
      if ($sourceResult->isFailure()) {
        return Fail(new NotUniqueValueExc("This profile picture already exists on the server."));
      }
      
      $source = $sourceResult->getSuccess();
      
      if (!preg_match("/^image.*/", mime_content_type($file->temporaryName))) {
        return fail(new InvalidArgumentExc("You douchebag, dumbfuck, absolute bafoon, the biggest pepega to ever walk under the sun. You cannot set a non-image file to profile picture dumb ass."));
      }
      
      
      $moveResult = $file->moveTo(HOSTS_DIR . "/$user->website/media/$source$file->ext");
      
      if ($moveResult->isFailure()) {
        return $moveResult;
      }
      
      self::delete($user->ID);
      
      return success(Database::get()->statement(
        "INSERT INTO " . self::TABLE_NAME . " (`src`, `usersID`, `hash`, `extension`)
        VALUE (:src, :userID, :hash, :extension)",
        [
          new DatabaseParam("src", $source, PDO::PARAM_STR),
          new DatabaseParam("userID", $user->ID),
          new DatabaseParam("hash", $hash, PDO::PARAM_STR),
          new DatabaseParam("extension", $file->ext, PDO::PARAM_STR),
        ]
      ));
    }
    public static function delete (int $userID): Result {
      $picturePathData = Database::get()->fetch(
        "SELECT
                users.website website,
                profilePictures.src src,
                profilePictures.extension ext
            FROM `profilePictures`
            	JOIN users ON users.ID = profilePictures.usersID
                	AND users.ID = :userID",
        stdClass::class,
        [new DatabaseParam("userID", $userID)]
      );
      
      if (!$picturePathData) {
        return fail(new NotFoundExc("Picture was not found."));
      }
      
      unlink(HOSTS_DIR . "/$picturePathData->website/media/$picturePathData->src$picturePathData->ext");
      
      return success(Database::get()->statement(
        "DELETE FROM " . self::TABLE_NAME . " WHERE usersID = :userID LIMIT 1",
        [new DatabaseParam("userID", $userID)]
      ));
    }
    
    
    
    public static function getByUserID (int $userID): Result {
      $picture = self::parseProps(Database::get()->fetch(
        "SELECT
        " . self::generateSelectColumns(self::TABLE_NAME, self::ALL_COLUMNS) . "
        FROM " . self::TABLE_NAME . "
        WHERE usersID = :userID",
        self::class,
        [new DatabaseParam("userID", $userID)]
      ));
      
      if (!$picture) {
        return fail(new NotFoundExc("User has not uploaded any profile picture yet."));
      }
      
      return success($picture);
    }
    
    public static function exists (string $hash, int $userID): bool {
      return Count::parseProps(Database::get()->fetch(
        "SELECT COUNT(*) amount
        FROM " . self::TABLE_NAME . "
        WHERE hash = :hash AND usersID = :userID",
        Count::class,
        [
          new DatabaseParam("hash", $hash, PDO::PARAM_STR),
          new DatabaseParam("userID", $userID),
        ]
      ))->amount != 0;
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
  }