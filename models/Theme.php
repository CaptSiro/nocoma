<?php

  require_once __DIR__ . "/../lib/modelist/modelist.php";
  require_once __DIR__ . "/../lib/retval/retval.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/Count.php";

  class Theme extends StrictModel {
    protected $src, $usersID, $name, $hash, $website;

    protected static function getNumberProps (): array { return ["usersID"]; }
    protected static function getBooleanProps (): array { return []; }
    
    const TABLE_NAME = "themes";
    const ALL_COLUMNS = ["src", "usersID", "name", "hash"];
    
    
    
    
    private const SET_SIZE = 20;
    public static function getSet (int $userID, int $offset) {
      return self::parseProps(Database::get()->fetchAll(
        "SELECT
            CASE WHEN themes.usersID = 0
                THEN CONCAT('_', themes.src)
                ELSE themes.src
            END as src,
            `name`,
            usersID
        FROM themes
        WHERE usersID IS NULL OR usersID = :userID
        LIMIT :offset, " . self::SET_SIZE,
        self::class,
        [
          new DatabaseParam("userID", $userID),
          new DatabaseParam("offset", $offset * self::SET_SIZE)
        ]
      ));
    }
    
    public static function getAllUsers (int $userID) {
      return self::parseProps(Database::get()->fetchAll(
        "SELECT
            CASE WHEN themes.usersID = 0
                THEN CONCAT('_', themes.src)
                ELSE themes.src
            END as src,
            `name`,
            usersID
        FROM themes
        WHERE usersID IS NULL OR usersID = 0 OR usersID = :userID
        ORDER BY usersID DESC",
        self::class,
        [new DatabaseParam("userID", $userID)]
      ));
    }
    
    
    public static function getDefaults () {
      return Database::get()->fetchAll(
        "SELECT
            CASE WHEN themes.usersID = 0
                THEN CONCAT('_', themes.src)
                ELSE themes.src
            END as src,
            `name`,
            usersID
        FROM themes
        WHERE usersID = 0",
        self::class
      );
    }
    
    
    public static function getBySRC (string $src): Result {
      $fetched = Database::get()->fetch(
        "SELECT
             CASE WHEN themes.usersID = 0
                 THEN CONCAT('_', themes.src)
                 ELSE themes.src
             END as src,
             `name`,
             usersID,
             hash,
             website
        FROM themes
        LEFT JOIN users ON users.ID = themes.usersID
        WHERE src = :src",
        self::class,
        [new DatabaseParam("src", $src, PDO::PARAM_STR)]
      );
      
      if ($fetched === false) {
        return fail(new NotFoundExc("This theme does not exist."));
      }
      
      return success(self::parseProps($fetched));
    }
    
    
    public static function bind (string $src, int $websiteID, int $userID): Result {
      $isThemeAccessibleToUser = Count::parseProps(Database::get()->fetch(
        "SELECT COUNT(themes.src) amount
        FROM themes
        JOIN websites ON themes.usersID = websites.usersID
          AND websites.usersID = :userID
          OR themes.usersID IS NULL
          AND themes.src = :src",
        Count::class,
        [
          new DatabaseParam("src", $src, PDO::PARAM_STR),
          new DatabaseParam("usersID", $userID)
        ]
      ))->amount == 1;
      
      if ($isThemeAccessibleToUser) {
        return fail(new IllegalArgumentExc("User can not access this resource: $src"));
      }
      
      return success(Database::get()->statement(
        "UPDATE websites SET themesSRC = :src WHERE ID = :id",
        [
          new DatabaseParam("src", $src, PDO::PARAM_STR),
          new DatabaseParam("id", $websiteID)
        ]
      ));
    }
    
    
    public static function delete (string $src, string $website): SideEffect {
      if (strlen($src) === 9 && $src[0] === "_") {
        return new SideEffect(0, 0);
      }
      
      Database::get()->statement(
        "UPDATE websites SET themesSRC = NULL WHERE themesSRC = :src",
        [new DatabaseParam("src", $src, PDO::PARAM_STR)]
      );
  
      Database::get()->statement(
        "DELETE FROM dynamicThemes WHERE themesSRC = :src LIMIT 1",
        [new DatabaseParam("src", $src, PDO::PARAM_STR)]
      );
      
      unlink(HOSTS_DIR . "/$website/media/$src.css");
      
      return Database::get()->statement(
        "DELETE FROM themes WHERE src = :src LIMIT 1",
        [new DatabaseParam("src", $src, PDO::PARAM_STR)]
      );
    }
    
    
    public static function rename (string $src, string $name): SideEffect {
      return Database::get()->statement(
        "UPDATE themes SET `name` = :name WHERE src = :src",
        [
          new DatabaseParam("name", $name, PDO::PARAM_STR),
          new DatabaseParam("src", $src, PDO::PARAM_STR),
        ]
      );
    }
    
    
    public static function insert (string $src, int $userID, string $name) {
      return Database::get()->statement(
        "INSERT INTO themes (src, usersID, `name`) VALUE (:src, :userID, :name)",
        [
          new DatabaseParam("src", $src, PDO::PARAM_STR),
          new DatabaseParam("userID", $userID),
          new DatabaseParam("name", $name, PDO::PARAM_STR),
        ]
      );
    }
  
    
    public static function isSRCValid (string $directory): Closure {
      return function ($src) use ($directory): Result {
        if ($src == "") {
          return fail(new InvalidArgumentExc("Source is not defined."));
        }
      
        $isNotPresentInDatabaseOrInDirectory = Count::parseProps(Database::get()->fetch(
            "SELECT COUNT(*) amount
            FROM `" . self::TABLE_NAME . "`
            WHERE src = :src",
            Count::class,
            [new DatabaseParam("src", $src, PDO::PARAM_STR)]
          ))->amount != 0 && empty(glob("$directory/$src.*"));
      
        return success($isNotPresentInDatabaseOrInDirectory);
      };
    }
    
    
    public static function isUnique (string $footprint, int $userID) {
      return Count::parseProps(Database::get()->fetch(
        "SELECT COUNT(themes.src) FROM themes
        WHERE `hash` != :footprint
            AND usersID = :userID",
        Count::class,
        [
          new DatabaseParam("footprint", $footprint, PDO::PARAM_STR),
          new DatabaseParam("userID", $userID)
        ]
      ))->amount == 0;
    }
  }