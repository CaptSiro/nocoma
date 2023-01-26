<?php

  require_once __DIR__ . "/../lib/modelist/modelist.php";
  require_once __DIR__ . "/../lib/retval/retval.php";

  class Website extends StrictModel {
    protected $ID, $usersID, $website, $thumbnailSRC, $thumbnail, $src, $timeCreated, $title,
      $isTemplate, $isPublic, $areCommentsAvailable, $isHomePage, $isTakenDown, $releaseDate;
    const ALL_COLUMNS = ["ID", "usersID", "thumbnailSRC", "src", "timeCreated", "title",
      "isTemplate", "isPublic", "isHomePage"];
    
    const TABLE_NAME = "websites";
    const PLANNED_WEBSITES_TABLE_NAME = "plannedwebsites";
    
    const IS_TAKEN_DOWN_CONDITION_PROJECTION = "(takedowns.websitesID IS NOT NULL) as isTakenDown";
    const IS_TAKEN_DOWN_CONDITION = "LEFT JOIN takedowns ON websites.ID = takedowns.websitesID";
    
    const JOIN_PLANNED_WEBSITES_PROJECTION = "plannedwebsites.releaseDate releaseDate";
    const JOIN_PLANNED_WEBSITES = "LEFT JOIN plannedwebsites ON plannedwebsites.websitesID = websites.ID";
    
    const THUMBNAIL_PROJECTION = "CONCAT(thumbnailMedia.src, thumbnailMedia.extension) as thumbnail";
    const THUMBNAIL = "LEFT JOIN media as thumbnailMedia ON websites.thumbnailSRC = thumbnailMedia.src";
  
    const WEBSITE_PROJECTION = "websiteUsers.website website";
    const WEBSITE = "JOIN users as websiteUsers ON websites.usersID = websiteUsers.ID";
    
    protected static function getNumberProps (): array { return ["ID", "usersID"]; }
    protected static function getBooleanProps (): array { return [
      "isTemplate", "isPublic", "isHomePage", "isTakenDown"
    ]; }
    
    
    
    public static function create (int $userID, string $title, string $src, int $isPublic, int $isHomePage): SideEffect {
      return Database::get()->statement(
        "INSERT INTO websites (`usersID`, `title`, `src`, `isPublic`, `isHomePage`)
        VALUE (:userID, :title, :src, :isPublic, :isHomePage)",
        [
          new DatabaseParam("userID", $userID),
          new DatabaseParam("title", $title, PDO::PARAM_STR),
          new DatabaseParam("src", $src, PDO::PARAM_STR),
          new DatabaseParam("isPublic", $isPublic),
          new DatabaseParam("isHomePage", $isHomePage),
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
    public static function setAsPlanned (int $websiteID, $releaseDate = null): SideEffect {
      $releaseDate = $releaseDate ?: (new DateTime())->format(DateTimeInterface::ATOM);
      
      $isAlreadyPlanned = Count::parseProps(Database::get()->fetch(
        "SELECT COUNT(*) amount FROM plannedwebsites WHERE websitesID = :websiteID",
        Count::class,
        [new DatabaseParam("websiteID", $websiteID)]
      ))->amount != 0;
      
      
      if ($isAlreadyPlanned) {
        return self::setReleaseDate($websiteID, $releaseDate);
      }
      
      Website::set(
        $websiteID,
        "isPublic",
        new DatabaseParam("isPublic", 0)
      );
      
      return Database::get()->statement(
        "INSERT INTO plannedwebsites (websitesID, releaseDate) VALUE (:websiteID, :releaseDate)",
        [
          new DatabaseParam("websiteID", $websiteID),
          new DatabaseParam("releaseDate", $releaseDate, PDO::PARAM_STR)
        ]
      );
    }
    public static function setReleaseDate (int $websiteID, $releaseDate = null): SideEffect {
      $releaseDate = $releaseDate ?: (new DateTime())->format("c");
      
      return Database::get()->statement(
        "UPDATE plannedwebsites SET releaseDate = :releaseDate WHERE websitesID = :websiteID",
        [
          new DatabaseParam("releaseDate", $releaseDate, PDO::PARAM_STR),
          new DatabaseParam("websiteID", $websiteID),
        ]
      );
    }
    public static function takeDown (int $websiteID, string $message): Result {
      try {
        return success(Database::get()->statement(
          "INSERT INTO `takeDowns` (`websitesID`, `message`)
         VALUES (:websiteID, :message)",
          [
            new DatabaseParam("websiteID", $websiteID),
            new DatabaseParam("message", $message, PDO::PARAM_STR)
          ]
        ));
      } catch (PDOException $exception) {
        return fail(new InvalidArgumentExc("This post is already taken down."));
      }
    }
    public static function removeTakeDown (int $websiteID): Result {
      return success(Database::get()->statement(
        "DELETE FROM `takeDowns` WHERE websitesID = :websiteID LIMIT 1",
        [new DatabaseParam("websiteID", $websiteID)]
      ));
    }
    public static function removePlannedStatus (int $websiteID): SideEffect {
      return Database::get()->statement(
        "DELETE FROM plannedwebsites WHERE websitesID = :websiteID LIMIT 1",
        [new DatabaseParam("websiteID", $websiteID)]
      );
    }
    
    public static function set (int $websiteID, string $column, DatabaseParam $param): SideEffect {
      return Database::get()->statement(
        "UPDATE websites SET $column = :$param->name WHERE ID = :websiteID",
        [new DatabaseParam("websiteID", $websiteID), $param]
      );
    }
    
    public static function getByID (int $id): Result {
      $post = Database::get()->fetch(
        "SELECT
            " . self::generateSelectColumns(self::TABLE_NAME, self::ALL_COLUMNS, true) . "
            " . self::IS_TAKEN_DOWN_CONDITION_PROJECTION . ",
            " . self::JOIN_PLANNED_WEBSITES_PROJECTION . ",
            " . self::THUMBNAIL_PROJECTION . ",
            " . self::WEBSITE_PROJECTION . "
        FROM
          `websites`
          " . self::IS_TAKEN_DOWN_CONDITION . "
          " . self::JOIN_PLANNED_WEBSITES . "
          " . self::THUMBNAIL . "
          " . self::WEBSITE . "
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
          " . self::generateSelectColumns(self::TABLE_NAME, self::ALL_COLUMNS, true) . "
          " . self::IS_TAKEN_DOWN_CONDITION_PROJECTION . ",
          " . self::JOIN_PLANNED_WEBSITES_PROJECTION . ",
          " . self::THUMBNAIL_PROJECTION . ",
          " . self::WEBSITE_PROJECTION . "
        FROM
          websites
          " . self::IS_TAKEN_DOWN_CONDITION . "
          " . self::JOIN_PLANNED_WEBSITES . "
          " . self::THUMBNAIL . "
          " . self::WEBSITE . "
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
          " . self::generateSelectColumns(self::TABLE_NAME, self::ALL_COLUMNS, true) . "
          " . self::IS_TAKEN_DOWN_CONDITION_PROJECTION . ",
          " . self::JOIN_PLANNED_WEBSITES_PROJECTION . ",
          " . self::THUMBNAIL_PROJECTION . ",
          users.website website
        FROM
          `websites`
          " . self::IS_TAKEN_DOWN_CONDITION . "
          " . self::JOIN_PLANNED_WEBSITES . "
          " . self::THUMBNAIL . "
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
          " . self::generateSelectColumns(self::TABLE_NAME, array_diff(self::ALL_COLUMNS, ["timeCreated"]), true) . "
          " . self::IS_TAKEN_DOWN_CONDITION_PROJECTION . ",
          MIN(websites.timeCreated) timeCreated,
          " . self::JOIN_PLANNED_WEBSITES_PROJECTION . ",
          " . self::THUMBNAIL_PROJECTION . "
        FROM
          `websites`
          " . self::IS_TAKEN_DOWN_CONDITION . "
          " . self::JOIN_PLANNED_WEBSITES . "
          " . self::THUMBNAIL . "
          JOIN users ON users.ID = websites.usersID
            AND users.website = :website
            AND websites.src = :source",
        self::class,
        [
          new DatabaseParam("website", $website, PDO::PARAM_STR),
          new DatabaseParam("source", $source, PDO::PARAM_STR),
        ]
      );
      
      if (!$post) {
        return fail(new NotFoundExc("This website does not exist."));
      }
  
      return success(self::parseProps($post));
    }
  
    public static function getBySourceWithUser (string $source): Result {
      $post = Database::get()->fetch(
        "SELECT
          " . self::generateSelectColumns(self::TABLE_NAME, array_diff(self::ALL_COLUMNS, ["timeCreated"]), true) . "
          " . self::IS_TAKEN_DOWN_CONDITION_PROJECTION . ",
          MIN(websites.timeCreated) timeCreated,
          " . self::JOIN_PLANNED_WEBSITES_PROJECTION . ",
          " . self::THUMBNAIL_PROJECTION . ",
          users.website website
        FROM
          `websites`
          " . self::IS_TAKEN_DOWN_CONDITION . "
          " . self::JOIN_PLANNED_WEBSITES . "
          " . self::THUMBNAIL . "
          JOIN users ON users.ID = websites.usersID
            AND websites.src = :source",
        self::class,
        [
          new DatabaseParam("source", $source, PDO::PARAM_STR),
        ]
      );
    
      if (!$post) {
        return fail(new NotFoundExc("This website does not exist."));
      }
    
      return success(self::parseProps($post));
    }
    
    
    const SET_RESTRICTION_ALL = 0;
    const SET_RESTRICTION_PUBLIC = 1;
    const SET_RESTRICTION_PLANNED = 2;
    const SET_RESTRICTION_PRIVATE = 3;
    private const SET_SIZE = 20;
    public static function getSet (int $userID, int $offset, $restriction = self::SET_RESTRICTION_ALL) {
      if ($restriction === self::SET_RESTRICTION_PLANNED) {
        return self::parseProps(Database::get()->fetchAll(
          "SELECT
          " . self::generateSelectColumns(self::TABLE_NAME, self::ALL_COLUMNS, true) . "
          " . self::IS_TAKEN_DOWN_CONDITION_PROJECTION . ",
          `" . self::PLANNED_WEBSITES_TABLE_NAME ."`.`releaseDate` as releaseDate,
          " . self::THUMBNAIL_PROJECTION . ",
          " . self::WEBSITE_PROJECTION . "
        FROM `websites`
          JOIN `" . self::PLANNED_WEBSITES_TABLE_NAME . "` ON `" . self::PLANNED_WEBSITES_TABLE_NAME . "`.websitesID = websites.ID
          " . self::IS_TAKEN_DOWN_CONDITION . ",
          " . self::THUMBNAIL . "
          " . self::WEBSITE . "
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
  
      $restrictions = ($restriction === self::SET_RESTRICTION_PRIVATE ? " AND websites.isPublic = 0" : "")
        . ($restriction === self::SET_RESTRICTION_PUBLIC ? " AND websites.isPublic = 1" : "");
      
      return self::parseProps(Database::get()->fetchAll(
        "SELECT
          " . self::generateSelectColumns(self::TABLE_NAME, self::ALL_COLUMNS, true) . "
          " . self::IS_TAKEN_DOWN_CONDITION_PROJECTION . ",
          " . self::JOIN_PLANNED_WEBSITES_PROJECTION . ",
          " . self::THUMBNAIL_PROJECTION . ",
          " . self::WEBSITE_PROJECTION . "
        FROM `websites`
          " . self::IS_TAKEN_DOWN_CONDITION . "
          " . self::JOIN_PLANNED_WEBSITES . "
          " . self::THUMBNAIL . "
          " . self::WEBSITE . "
        WHERE websites.usersID = :userID $restrictions
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