<?php

  require_once __DIR__ . "/../lib/modelist/modelist.php";
  require_once __DIR__ . "/../lib/retval/retval.php";

  class Website extends StrictModel {
    protected $ID, $src, $usersID, $thumbnailSRC, $timeCreated, $title,
      $description, $isTemplate, $isPublic, $areCommentsAvailable, $isHomePage, $isTakenDown;

    protected static function getNumberProps (): array { return ["ID", "userID", "templateStyle"]; }
    protected static function getBooleanProps (): array { return ["isPublic", "areCommentsEnabled", "isHomepage"]; }
    
    
    
    public static function create (int $userID, string $title, string $src): SideEffect {
      return Database::get()->statement(
        "INSERT INTO websites (`usersID`, `title`, `src`) VALUE (:userID, :title, :src)",
        [
          new DatabaseParam("userID", $userID),
          new DatabaseParam("title", $title, PDO::PARAM_STR),
          new DatabaseParam("src", $src, PDO::PARAM_STR),
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
  
    /**
     * @param string $website
     * @return Result<Website>
     */
    public static function getHomePage (string $website): Result {
      $post = Database::get()->fetch(
        "SELECT
          websites.ID ID,
          websites.usersID usersID,
          websites.src src,
          websites.thumbnailSRC thumbnailSRC,
          websites.timeCreated timeCreated,
          websites.title title,
          websites.description description,
          websites.isTemplate isTemplate,
          websites.isPublic isPublic,
          websites.areCommentsAvailable areCommentsAvailable,
          websites.isHomePage isHomePage
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
          websites.ID,
          websites.usersID,
          websites.thumbnailSRC,
          MIN(websites.timeCreated) timeCreated,
          websites.src,
          websites.title,
          websites.description,
          websites.isTemplate,
          websites.isPublic,
          websites.areCommentsAvailable,
          websites.isHomePage,
          websites.isTakenDown
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
    
    public static function getBySource (string $website, string $source): Result {
      $post = Database::get()->fetch(
        "SELECT
          websites.ID,
          websites.usersID,
          websites.thumbnailSRC,
          MIN(websites.timeCreated) timeCreated,
          websites.src,
          websites.title,
          websites.description,
          websites.isTemplate,
          websites.isPublic,
          websites.areCommentsAvailable,
          websites.isHomePage,
          websites.isTakenDown
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
  
      if (!isset($post->ID) || (isset($post->isPublic) && $post->isPublic == "0")) {
        return fail(new NotFoundExc("This website does not exist."));
      }
  
      if (isset($post->isTakenDown) && $post->isTakenDown == "1") {
        return fail(new IllegalArgumentExc("This website is no longer accessible."));
      }
  
      return success(self::parseProps($post));
    }
    
    
    public static function isSrcValid (): Closure {
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