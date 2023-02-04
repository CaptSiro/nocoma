<?php

  require_once(__DIR__ . "/../lib/modelist/modelist.php");
  
  require_once __DIR__ . "/Website.php";

  class Comment extends StrictModel {
    protected $ID, $websitesID, $usersID, $username, $level, $creatorID, $parentCommentID, $timePosted, $content, $reactionCount, $childrenCount, $isPinned, $reaction;
    
    const TABLE_NAME = "comments";
    const ALL_COLUMNS = ["ID", "websitesID", "usersID", "parentCommentID", "timePosted", "content"];

    protected static function getNumberProps (): array { return ["ID", "websiteID", "creatorID", "parentCommentID", "level", "usersID", "childrenCount"]; }
    protected static function getBooleanProps (): array { return ["isPinned"]; }
    
    
    
    private const SET_SIZE = 20;
    public static function getSet (int $websiteID, int $requestUserID, int $offset, int $parentCommentID = -1) {
      if ($parentCommentID > 0) {
        return self::parseProps(Database::get()->fetchAll(
          "SELECT
            ". self::generateSelectColumns(self::TABLE_NAME, self::ALL_COLUMNS, true) ."
            `reactionSum`.`sum` reactionCount,
            websites.usersID creatorID,
            users.username username,
            users.level level,
            reactions.`value` \"reaction\"
          FROM `comments`
            JOIN users ON comments.usersID = users.ID
              AND users.isDisabled = 0
              AND users.isVerified = 1
            LEFT JOIN (
          	  SELECT SUM(reactions.value) as \"sum\", reactions.commentsID as ID FROM `reactions`
          	  WHERE reactions.usersID != :requestUserID
              GROUP BY 2
            ) reactionSum ON reactionSum.ID = comments.ID
            JOIN websites ON websites.ID = :websiteID
            LEFT JOIN reactions ON comments.ID = reactions.commentsID
              AND reactions.usersID = :requestUserID
          WHERE comments.websitesID = :websiteID
            AND parentCommentID = :parentCommentID
          ORDER BY reactionSum.sum DESC
          LIMIT :offset, " . self::SET_SIZE,
          self::class,
          [
            new DatabaseParam("websiteID", $websiteID),
            new DatabaseParam("requestUserID", $requestUserID),
            new DatabaseParam("parentCommentID", $parentCommentID),
            new DatabaseParam("offset", $offset * self::SET_SIZE)
          ]
        ));
      }
      
      return self::parseProps(Database::get()->fetchAll(
        "SELECT
            ". self::generateSelectColumns(self::TABLE_NAME, self::ALL_COLUMNS, true) ."
            `reactionSum`.`sum` reactionCount,
            (pinnedComments.commentsID IS NOT NULL) isPinned,
            websites.usersID creatorID,
            users.username username,
            users.level level,
            children.amount childrenCount,
            reactions.`value` \"reaction\"
          FROM `comments`
            JOIN users ON comments.usersID = users.ID
              AND users.isDisabled = 0
              AND users.isVerified = 1
            LEFT JOIN (
          	  SELECT SUM(reactions.value) as \"sum\", reactions.commentsID as ID FROM `reactions`
          	  WHERE reactions.usersID != :requestUserID
              GROUP BY 2
            ) reactionSum ON reactionSum.ID = comments.ID
            JOIN websites ON websites.ID = :websiteID
            LEFT JOIN pinnedComments ON pinnedComments.commentsID = comments.ID
            LEFT JOIN reactions ON comments.ID = reactions.commentsID
              AND reactions.usersID = :requestUserID
            LEFT JOIN (
              SELECT COUNT(comments.ID) as amount, comments.parentCommentID parentsID FROM comments
              WHERE comments.parentCommentID IS NOT NULL
              GROUP BY 2
            ) as children ON children.parentsID = comments.ID
          WHERE comments.websitesID = :websiteID
            AND parentCommentID IS NULL
          ORDER BY case when pinnedComments.commentsID is null then 1 else 0 end, reactionSum.sum DESC
          LIMIT :offset, " . self::SET_SIZE,
        self::class,
        [
          new DatabaseParam("websiteID", $websiteID),
          new DatabaseParam("requestUserID", $requestUserID),
          new DatabaseParam("offset", $offset * self::SET_SIZE)
        ]
      ));
    }
    
    public static function getByID (int $commentID) {
      return self::parseProps(Database::get()->fetch(
        "SELECT
          " . self::generateSelectColumns(self::TABLE_NAME, self::ALL_COLUMNS, true) . "
          websites.usersID creatorID
        FROM `" . self::TABLE_NAME . "`
        JOIN `". Website::TABLE_NAME ."` ON `". Website::TABLE_NAME ."`.ID = `". self::TABLE_NAME ."`.websitesID
        WHERE `" . self::TABLE_NAME . "`.`ID` = :commentID",
        self::class,
        [new DatabaseParam("commentID", $commentID)]
      ));
    }
    
    public static function countTotal (int $websiteID) {
      return (Database::get()->fetch(
        "SELECT COUNT(*) amount
        FROM comments
        WHERE comments.websitesID = :websiteID",
        "stdClass",
        [new DatabaseParam("websiteID", $websiteID)]
      ))->amount;
    }
    
    public static function setReaction (int $commentID, int $userID, int $value): SideEffect {
      $params = [
        new DatabaseParam("commentID", $commentID),
        new DatabaseParam("userID", $userID),
      ];
      
      if ($value === 0) {
        return Database::get()->statement(
          "DELETE FROM `reactions` WHERE commentsID = :commentID AND usersID = :userID LIMIT 1",
          $params
        );
      }
      
      $isSet = Count::parseProps(Database::get()->fetch(
          "SELECT COUNT(*) amount
            FROM reactions
            WHERE usersID = :userID AND commentsID = :commentID",
          Count::class,
          [
            new DatabaseParam("userID", $userID),
            new DatabaseParam("commentID", $commentID),
          ]
      ))->amount !== 0;
      
      $params[] = new DatabaseParam("value", $value);
      
      if ($isSet) {
        return Database::get()->statement(
          "UPDATE `reactions` SET value = :value WHERE commentsID = :commentID AND usersID = :userID",
          $params
        );
      }
      
      return Database::get()->statement(
        "INSERT INTO `reactions` (commentsID, usersID, `value`) VALUE (:commentID, :userID, :value)",
        $params
      );
    }
    
    public static function setIsPinned (int $commentID, bool $isPinned) {
      $params = [new DatabaseParam("commentID", $commentID)];
      
      if ($isPinned === false) {
        return Database::get()->statement(
          "DELETE FROM pinnedComments WHERE commentsID = :commentID LIMIT 1",
          $params
        );
      }
      
      return Database::get()->statement(
        "INSERT INTO pinnedComments (commentsID) VALUE (:commentID)",
        $params
      );
    }
    
    public static function delete (int $commentID): SideEffect {
      $param = [new DatabaseParam("commentID", $commentID)];
      
      Database::get()->statement(
        "DELETE FROM reactions WHERE commentsID = :commentID",
        $param
      );
      
      Database::get()->statement(
        "DELETE FROM pinnedComments WHERE commentsID = :commentID LIMIT 1",
        $param
      );
  
      Database::get()->statement(
        "DELETE FROM ". self::TABLE_NAME ." WHERE parentCommentID = :commentID",
        $param
      );

      return Database::get()->statement(
        "DELETE FROM ". self::TABLE_NAME ." WHERE ID = :commentID LIMIT 1",
        $param
      );
    }
    
    public static function insert (int $websitesID, int $usersID, string $content, $parentCommentID = null) {
      if ($parentCommentID !== null) {
        $parentCommentID = intval($parentCommentID);
      }
      
      return Database::get()->statement(
        "INSERT INTO comments (websitesID, usersID, content, parentCommentID) VALUE (:websitesID, :usersID, :content, :parentCommentID)",
        [
          new DatabaseParam("websitesID", $websitesID),
          new DatabaseParam("usersID", $usersID),
          new DatabaseParam("content", $content, PDO::PARAM_STR),
          new DatabaseParam("parentCommentID", $parentCommentID),
        ]
      );
    }
  }