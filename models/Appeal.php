<?php
  
  require_once __DIR__ . "/../lib/modelist/modelist.php";
  require_once __DIR__ . "/../lib/retval/retval.php";
  
  require_once __DIR__ . "/User.php";
  
  class Appeal extends StrictModel {
    protected $ID, $takedownsID, $message, $hasBeenRead, $dateAdded, $hasBeenAccepted;
    const ALL_COLUMNS = ["ID", "takedownsID", "message", "hasBeenRead", "dateAdded", "hasBeenAccepted"];
    const TABLE_NAME = "appeals";
    
    protected static function getNumberProps(): array { return ["ID", "takedownsID"]; }
    protected static function getBooleanProps(): array { return ["hasBeenRead", "hasBeenAccepted"]; }
  
  
    public static function create (int $websiteID, string $message = null): Result {
      if (self::getByWebsiteID($websiteID)->isSuccess()) {
        return fail(new IllegalArgumentExc("There already exists appeal for this post."));
      }
    
      if ($message === null) {
        return success(Database::get()->statement(
          "INSERT INTO `appeals`(`takedownsID`) VALUES
            (:websiteID)",
          [
            new DatabaseParam("websiteID", $websiteID)
          ]
        ));
      }
    
      return success(Database::get()->statement(
        "INSERT INTO `appeals`(`takedownsID`, `message`) VALUES
            (:websiteID, :message)",
        [
          new DatabaseParam("websiteID", $websiteID),
          new DatabaseParam("message", $message, PDO::PARAM_STR)
        ]
      ));
    }
    
    
    
    public static function setAsRead (int $id): SideEffect {
      return Database::get()->statement(
        "UPDATE `appeals` SET `hasBeenRead` = 1 WHERE `appeals`.`ID` = :id LIMIT 1",
        [new DatabaseParam("id", $id)]
      );
    }
    public static function accept (int $id): Result {
      $appealResult = self::get($id);
      if ($appealResult->isFailure()) {
        return $appealResult;
      }
      
      /**
       * @var Appeal $appeal
       */
      $appeal = $appealResult->getSuccess();
      
      return Website::removeTakeDown($appeal->takedownsID);
    }
    public static function delete (int $id): Result {
      return success(Database::get()->statement(
        "DELETE FROM `appeals` WHERE `ID` = :id LIMIT 1",
        [new DatabaseParam("id", $id)]
      ));
    }
  
  
  
    public static function get (int $id): Result {
      $fetched = Database::get()->fetch(
        "SELECT
                " . self::generateSelectColumns(self::TABLE_NAME, self::ALL_COLUMNS) . "
            FROM
                appeals
            WHERE
                appeals.ID = :id",
        "stdClass",
        [
          new DatabaseParam("id", $id)
        ]
      );
  
      if (!$fetched) {
        return fail(new InvalidArgumentExc("No appeals found for given website."));
      }
  
      return success($fetched);
    }
    public static function getByWebsiteID (int $websiteID): Result {
      $fetched = Database::get()->fetch(
        "SELECT
                " . self::generateSelectColumns(self::TABLE_NAME, self::ALL_COLUMNS) . "
            FROM
                appeals
            WHERE
                appeals.takedownsID = :websiteID",
        "stdClass",
        [
          new DatabaseParam("websiteID", $websiteID)
        ]
      );
    
      if (!$fetched) {
        return fail(new InvalidArgumentExc("No appeals found for given website."));
      }
    
      return success($fetched);
    }
  
  
  
    const SET_RESTRICTION_ALL = 0;
    const SET_RESTRICTION_NOT_READ = 1;
    private const SET_SIZE = 20;
    public static function getSet (int $offset, $restriction = self::SET_RESTRICTION_ALL) {
      return User::parseProps(Website::parseProps(self::parseProps(Database::get()->fetchAll(
        "SELECT
            " . self::generateSelectColumns(self::TABLE_NAME, self::ALL_COLUMNS, true) . "
            " . self::generateSelectColumns(User::TABLE_NAME, ["username", "website", "email"], true) . "
            " . self::generateSelectColumns(Website::TABLE_NAME, ["title", "src", "usersID"]) . "
        FROM
          `appeals`
          JOIN websites ON websites.ID = appeals.takedownsID
          JOIN users ON websites.usersID = users.ID
        WHERE " . (($restriction === self::SET_RESTRICTION_ALL)
          ? "1"
          : ($restriction === self::SET_RESTRICTION_NOT_READ
            ? "hasBeenRead = 0"
            : "1")) . "
        ORDER BY hasBeenRead ASC, dateAdded DESC
        LIMIT :offset, " . self::SET_SIZE,
        "stdClass",
        [
          new DatabaseParam("offset", $offset * self::SET_SIZE),
        ]
      ))));
    }
  }