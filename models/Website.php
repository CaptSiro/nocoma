<?php

  require_once __DIR__ . "/../lib/modelist/modelist.php";
  require_once __DIR__ . "/../lib/retval/retval.php";

  class Website extends StrictModel {
    protected $ID, $usersID, $thumbnailSRC, $src, $timeCreated, $title,
      $isTemplate, $isPublic, $areCommentsAvailable, $isHomePage, $isTakenDown;
    const ALL_COLUMNS = ["ID", "usersID", "thumbnailSRC", "src", "timeCreated", "title",
      "isTemplate", "isPublic", "areCommentsAvailable", "isHomePage", "isTakenDown"];

    protected static function getNumberProps (): array { return ["ID", "userID"]; }
    protected static function getBooleanProps (): array { return [
      "isTemplate", "isPublic", "areCommentsAvailable", "isHomePage", "isTakenDown"
    ]; }
    
    
    
    public static function create (int $userID, string $title, string $src, int $isPublic, int $isHomePage, $areCommentsAvailable): SideEffect {
      return Database::get()->statement(
        "INSERT INTO websites (`usersID`, `title`, `src`, `isPublic`, `isHomePage`, `areCommentsAvailable`)
        VALUE (:userID, :title, :src, :isPublic, :isHomePage, :areCommentsAvailable)",
        [
          new DatabaseParam("userID", $userID),
          new DatabaseParam("title", $title, PDO::PARAM_STR),
          new DatabaseParam("src", $src, PDO::PARAM_STR),
          new DatabaseParam("isPublic", $isPublic),
          new DatabaseParam("isHomePage", $isHomePage),
          new DatabaseParam("areCommentsAvailable", $areCommentsAvailable),
        ]
      );
    }
    
    
    
    private static function updateIsHomePage (int $websiteID, int $value) {
      Database::get()->statement(
        "UPDATE websites SET isHomePage = :value WHERE ID = :websiteID",
        [
          new DatabaseParam("websiteID", $websiteID),
          new DatabaseParam("value", $value)
        ]
      );
    }
    public static function setIsHomePage (int $websiteID, bool $value = true): int {
      if ($value) {
        // reset all pages to not be home page
        $userID = Database::get()->fetch(
          "SELECT websites.usersID usersID FROM websites WHERE websites.ID = :websiteID;",
          "stdClass",
          [new DatabaseParam("websiteID", $websiteID)]
        )->usersID;
        
        Database::get()->statement(
          "UPDATE websites SET isHomePage = 0 WHERE usersID = :userID",
          [new DatabaseParam("userID", $userID)]
        );
      }
      
      self::updateIsHomePage($websiteID, (int)$value);
      
      return 1;
    }
  
    public static function getByID (int $id): Result {
      $post = Database::get()->fetch(
        "SELECT
            " . self::generateSelectColumns("websites", self::ALL_COLUMNS) . "
        FROM
          `websites`
        WHERE websites.ID = :id",
        self::class,
        [new DatabaseParam("id", $id)]
      );
    
      if (!isset($post->ID)) {
        return fail(new NotFoundExc("Could not find website by given ID."));
      }
    
      return success(self::parseProps($post));
    }
  
    /**
     * @param string $website
     * @return Result<Website>
     */
    public static function getHomePage (string $website): Result {
      $post = Database::get()->fetch(
        "SELECT
          " . self::generateSelectColumns("websites", self::ALL_COLUMNS) . "
        FROM
          websites
          JOIN users ON users.ID = websites.usersID
            AND websites.isHomePage = 1
            AND users.website = :website",
        "Website",
        [new DatabaseParam("website", $website, PDO::PARAM_STR)]
      );
      
      if (!isset($post->ID)) {
        return fail(new NotFoundExc("Could not find home page for this domain."));
      }
      
      return success(self::parseProps($post));
    }
  
    /**
     * @param string $website
     * @return Result<Website>
     */
    public static function getOldestPage (string $website): Result {
      $post = Database::get()->fetch(
        "SELECT
          " . self::generateSelectColumns("websites", self::ALL_COLUMNS) . "
        FROM
          `websites`
          JOIN users ON users.ID = websites.usersID
            AND websites.isPublic = 1
            AND websites.isTakenDown = 0
            AND users.website = :website",
        self::class,
        [new DatabaseParam("website", $website, PDO::PARAM_STR)]
      );
      
      if (!isset($post->ID)) {
        return fail(new NotFoundExc("This website does not have any public posts."));
      }
      
      return success(self::parseProps($post));
    }
    
    public static function getBySource (string $website, string $source, bool $bypassPublicConstraint = false): Result {
      $post = Database::get()->fetch(
        "SELECT
          " . self::generateSelectColumns("websites", array_diff(self::ALL_COLUMNS, ["timeCreated"]), true) . "
          MIN(websites.timeCreated) timeCreated
        FROM
          `websites`
          JOIN users ON users.ID = websites.usersID
            AND users.website = :website
            AND websites.src = :source",
        self::class,
        [
          new DatabaseParam("website", $website, PDO::PARAM_STR),
          new DatabaseParam("source", $source, PDO::PARAM_STR),
        ]
      );
  
      $postDoesNotExists = !isset($post->ID);
      $postIsNotAccessible = !((isset($post->isPublic) && $post->isPublic == "1") || ($bypassPublicConstraint));
      
      if ($postDoesNotExists || $postIsNotAccessible) {
        return fail(new NotFoundExc("This website does not exist."));
      }
  
      if (isset($post->isTakenDown) && $post->isTakenDown == "1") {
        return fail(new IllegalArgumentExc("This website is no longer accessible."));
      }
  
      return success(self::parseProps($post));
    }
    
    
    private const SET_SIZE = 20;
    public static function getSet (int $userID, int $offset) {
      return self::parseProps(Database::get()->fetchAll(
        "SELECT
          " . self::generateSelectColumns("websites", self::ALL_COLUMNS) . "
        FROM `websites`
        WHERE websites.usersID = :userID
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
          Count::parseProps(
            Database::get()->fetch(
              "SELECT COUNT(*) amount
                FROM `websites`
                WHERE src = :src",
              Count::class,
              [new DatabaseParam("src", $src, PDO::PARAM_STR)]
            )
          )->amount != 0
        );
      };
    }
    
    
    
    public static function delete (string $source): SideEffect {
      return Database::get()->statement(
        "DELETE FROM websites WHERE src = :source LIMIT 1",
        [new DatabaseParam("source", $source, PDO::PARAM_STR)]
      );
    }
  }