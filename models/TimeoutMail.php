<?php

  require_once __DIR__ . "/../lib/modelist/modelist.php";
  require_once __DIR__ . "/../lib/retval/retval.php";
  require_once __DIR__ . "/User.php";
  require_once __DIR__ . "/Count.php";

  class TimeoutMail extends StrictModel {
    protected $ID, $usersID, $expires, $passwordRecoveriesID, $urlArg, $verificationCodesID, $code, $state;
    public const PASSWORD_RECOVERY = 0;
    public const VERIFICATION_CODE = 1;

    protected static function getNumberProps (): array {
      return ["ID", "usersID", "expires", "passwordRecoveriesID", "verificationCodesID", "code"];
    }


    protected static function getBooleanProps (): array {
      return [];
    }


    private function finish () {
      if (isset($this->passwordRecoveriesID)) {
        $this->state = self::PASSWORD_RECOVERY;
      }

      if (isset($this->verificationCodesID)) {
        $this->state = self::VERIFICATION_CODE;
      }
    }






    public static $timeout = 60 * 5; // 5 minutes
    private static function insert ($userID): SideEffect {
      return Database::get()->statement(
        "INSERT INTO `timeoutMails`(`usersID`, `expires`)
        VALUES (:usersID, :expires)",
        [
          new DatabaseParam("usersID", $userID),
          new DatabaseParam("expires", time() + self::$timeout)
        ]
      );
    }

    private static function delete (int $timeoutMailID): SideEffect {
      return Database::get()->statement(
        "DELETE FROM `timeoutMails`
        WHERE ID = :tmID
        LIMIT 1",
        [new DatabaseParam("tmID", $timeoutMailID)]
      );
    }






    public static function getUsersMails ($usersID): array {
      $mails = self::parseProps(Database::get()->fetchAll(
        "SELECT
          timeoutMails.ID ID,
          timeoutMails.usersID usersID,
          timeoutMails.expires expires,
          pr.passwordRecoveriesID passwordRecoveriesID,
          pr.recoveryID recoveryID,
          vc.verificationCodesID verificationCodesID,
          vc.code \"code\"
        FROM
          `timeoutMails`
          JOIN users ON users.ID = :userID
          LEFT JOIN passwordRecoveries AS pr ON pr.passwordRecoveriesID = timeoutMails.ID
          LEFT JOIN verificationCodes AS vc ON vc.verificationCodesID = timeoutMails.ID",
        self::class,
        [new DatabaseParam("userID", $usersID)]
      ));

      foreach ($mails as $mail) {
        $mail->finish();
      }

      return $mails;
    }


    public static function purgeOld (): void {
      Database::get()->statement("DELETE FROM `timeoutMails` WHERE `expires` - UNIX_TIMESTAMP() < 0");
    }




    
    public static function isCodeTaken (): Closure {
      return function ($code): Result {
        if ($code == "") {
          return fail(new InvalidArgumentExc("Code is not defined."));
        }
  
        return success(
          Count::parseProps(
            Database::get()->fetch(
              "SELECT COUNT(*) amount
                FROM `verificationCodes`
                WHERE code = :code",
              Count::class,
              [new DatabaseParam("code", $code, PDO::PARAM_STR)]
            )
          )->amount != 0
        );
      };
    }


    public static function insertCode (string $code, int $userID): Result {
      if ($code == "") {
        return fail(new InvalidArgumentExc("Code is not defined."));
      }

      $sideEffect = self::insert($userID);
      $timeoutMailID = $sideEffect->lastInsertedID;

      Database::get()->statement(
        "INSERT INTO `verificationCodes`(`verificationCodesID`, `code`)
        VALUES (:tmID, :code)",
        [
          new DatabaseParam("tmID", $timeoutMailID),
          new DatabaseParam("code", $code, PDO::PARAM_STR)
        ]
      );

      return success($timeoutMailID);
    }


    public static function getUserWithCode (string $code): Result {
      if ($code == "") {
        return fail(new InvalidArgumentExc("Code is not defined."));
      }

      $optionalUser = Database::get()->fetch(
        "SELECT
          " . User::generateSelectColumns(User::TABLE_NAME, User::ALL_COLUMNS, true) . "
          timeoutMails.expires as expires
        FROM
          `timeoutMails`
          JOIN verificationCodes as vc ON vc.verificationCodesID = timeoutMails.ID
            AND vc.code = :code
          JOIN users ON users.ID = timeoutMails.usersID",
        User::class,
        [new DatabaseParam("code", $code, PDO::PARAM_STR)]
      );

      if ($optionalUser === false) {
        return fail(new NotFoundExc("Could not found user."));
      }

      if (intval($optionalUser->expires) - time() < 0) {
        return fail(new InvalidArgumentExc("Code has expired."));
      }

      return success($optionalUser);
    }


    public static function removeCode (string $code): Result {
      if ($code == "") {
        return fail(new InvalidArgumentExc("Code is not defined"));
      }

      $mail = self::parseProps(Database::get()->fetch(
        "SELECT verificationCodesID
        FROM `verificationCodes`
        WHERE code = :code",
        self::class,
        [new DatabaseParam("code", $code, PDO::PARAM_STR)]
      ));

      $sideEffect = Database::get()->statement(
        "DELETE FROM timeoutMails
        WHERE ID = :tmID
        LIMIT 1",
        [new DatabaseParam("tmID", $mail->verificationCodesID)]
      );

      if ($sideEffect->rowCount == 0) {
        return fail(new NotFoundExc("Could not found timeout mail with code: '$code'"));
      }
      
      return success("Successful.");
    }


    public static function removeCodesFor (int $userID): Result {
      if ($userID < 1) {
        return fail(new InvalidArgumentExc("User ID is not defined."));
      }

      $mails = self::parseProps(Database::get()->fetchAll(
        "SELECT
          `timeoutMails`.`ID` ID
        FROM
          `timeoutMails`
          JOIN verificationCodes AS vc ON (timeoutMails.ID, timeoutMails.usersID) = (vc.verificationCodesID, :userID)",
        self::class,
        [new DatabaseParam("userID", $userID)]
      ));

      $deleted = 0;
      foreach ($mails as $mail) {
        $sideEffect = self::delete($mail->ID);

        if ($sideEffect->rowCount == 1) {
          $deleted++;
        }
      }

      return success($deleted);
    }






    






    
    public static function isURLArgumentTaken (): Closure {
      return function ($urlArgument) {
        if ($urlArgument == "") {
          return fail(new InvalidArgumentExc("Url argument is not defined."));
        }
  
        return success(Count::parseProps(Database::get()->fetch(
            "SELECT COUNT(*) amount
            FROM `passwordRecoveries`
            WHERE urlArg = :urlArg",
            Count::class,
            [new DatabaseParam("urlArg", $urlArgument, PDO::PARAM_STR)]
          ))->amount != 0);
      };
    }


    public static function insertUrlArg (string $urlArg, int $userID): Result {
      if ($urlArg == "") {
        return fail(new InvalidArgumentExc("Url argument is not defined."));
      }

      $sideEffect = self::insert($userID);
      $timeoutMailID = $sideEffect->lastInsertedID;
      
      Database::get()->statement(
        "INSERT INTO `passwordRecoveries`(`passwordRecoveriesID`, `urlArg`)
        VALUES (:tmID, :urlArg)",
        [
          new DatabaseParam("tmID", $timeoutMailID),
          new DatabaseParam("urlArg", $urlArg, PDO::PARAM_STR)
        ]
      );

      return success($timeoutMailID);
    }


    public static function getUserWithUA (string $urlArg): Result {
      if ($urlArg == "") {
        return fail(new InvalidArgumentExc("Url argument is not defined."));
      }

      $optionalUser = Database::get()->fetch(
        "SELECT
          ". User::generateSelectColumns(User::TABLE_NAME, User::ALL_COLUMNS, true) ."
          timeoutMails.expires as expires
        FROM
          `timeoutMails`
          JOIN passwordRecoveries as pr ON pr.passwordRecoveriesID = timeoutMails.ID
            AND pr.urlArg = :urlArg
            -- AND timeoutMails.expires - UNIX_TIMESTAMP() >= 0
          JOIN users ON users.ID = timeoutMails.usersID",
        stdClass::class,
        [new DatabaseParam("urlArg", $urlArg, PDO::PARAM_STR)]
      );

      if ($optionalUser === false) {
        return fail(new NotFoundExc("Not a valid URL."));
      }

      if (intval($optionalUser->expires) - time() < 0) {
        self::purgeOld();
        return fail(new InvalidArgumentExc("URL has expired."));
      }

      return success($optionalUser);
    }


    public static function removeUA (string $ua): Result {
      if ($ua == "") {
        return fail(new InvalidArgumentExc("Url argument is not defined"));
      }

      $mail = self::parseProps(Database::get()->fetch(
        "SELECT passwordRecoveriesID
        FROM `passwordRecoveries`
        WHERE urlArg = :ua",
        self::class,
        [new DatabaseParam("ua", $ua, PDO::PARAM_STR)]
      ));

      $sideEffect = Database::get()->statement(
        "DELETE FROM timeoutMails
        WHERE ID = :tmID
        LIMIT 1",
        [new DatabaseParam("tmID", $mail->passwordRecoveriesID)]
      );

      if ($sideEffect->rowCount == 0) {
        return fail(new NotFoundExc("Could not found timeout mail with url argument: '$ua'"));
      }
      
      return success("Successfull.");
    }


    public static function removeUAsFor (int $userID): Result {
      if ($userID < 1) {
        return fail(new InvalidArgumentExc("User ID is not defined."));
      }

      $mails = self::parseProps(Database::get()->fetchAll(
        "SELECT
          `timeoutMails`.`ID` ID
        FROM
          `timeoutMails`
          JOIN passwordRecoveries AS pr ON (timeoutMails.ID, timeoutMails.usersID) = (pr.passwordRecoveriesID, :userID)",
        self::class,
        [new DatabaseParam("userID", $userID)]
      ));

      $deleted = 0;
      foreach ($mails as $mail) {
        $sideEffect = self::delete($mail->ID);

        if ($sideEffect->rowCount == 1) {
          $deleted++;
        }
      }

      return success($deleted);
    }
  }