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
        "INSERT INTO `timeoutmails`(`usersID`, `expires`)
        VALUES (:usersID, :expires)",
        [
          new DatabaseParam("usersID", $userID),
          new DatabaseParam("expires", time() + self::$timeout)
        ]
      );
    }

    private static function delete (int $timeoutMailID): SideEffect {
      return Database::get()->statement(
        "DELETE FROM `timeoutmails`
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
          `timeoutmails`
          JOIN users ON users.ID = :userID
          LEFT JOIN passwordrecoveries AS pr ON pr.passwordRecoveriesID = timeoutmails.ID
          LEFT JOIN verificationcodes AS vc ON vc.verificationCodesID = timeoutmails.ID",
        self::class,
        [new DatabaseParam("userID", $usersID)]
      ));

      foreach ($mails as $mail) {
        $mail->finish();
      }

      return $mails;
    }


    public static function purgeOld (): void {
      Database::get()->statement("DELETE FROM `timeoutmails` WHERE `expires` - UNIX_TIMESTAMP() < 0");
    }






    public static function genCode (): string {
      return join("", array_map(
        function ($v) {
          return random_int(0, 9);
        },
        array_fill(0, 6, null)
      ));
    }


    public static function isCodeTaken (string $code): Result {
      if ($code == "") {
        return fail(new InvalidArgumentExc("Code is not defined."));
      }

      return success(Count::parseProps(Database::get()->fetch(
        "SELECT COUNT(*) amount
        FROM `verificationcodes`
        WHERE code = :code",
        Count::class,
        [new DatabaseParam("code", $code, PDO::PARAM_STR)]
      ))->amount != 0);
    }


    public static function insertCode (string $code, int $userID): Result {
      if ($code == "") {
        return fail(new InvalidArgumentExc("Code is not defined."));
      }

      $sideEffect = self::insert($userID);
      $timeoutMailID = $sideEffect->lastInsertedID;

      Database::get()->statement(
        "INSERT INTO `verificationcodes`(`verificationCodesID`, `code`)
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
          users.ID ID,
          users.profileSRC profileSRC,
          users.email email,
          users.password \"password\",
          users.level \"level\",
          users.website website,
          timeoutmails.expires as expires
        FROM
          `timeoutmails`
          JOIN verificationcodes as vc ON vc.verificationCodesID = timeoutmails.ID
            AND vc.code = :code
          JOIN users ON users.ID = timeoutmails.usersID",
        stdClass::class,
        [new DatabaseParam("code", $code, PDO::PARAM_STR)]
      );

      if ($optionalUser == false) {
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
        FROM `verificationcodes`
        WHERE code = :code",
        self::class,
        [new DatabaseParam("code", $code, PDO::PARAM_STR)]
      ));

      $sideEffect = Database::get()->statement(
        "DELETE FROM timeoutmails
        WHERE ID = :tmID
        LIMIT 1",
        [new DatabaseParam("tmID", $mail->verificationCodesID)]
      );

      if ($sideEffect->rowCount == 0) {
        return fail(new NotFoundExc("Could not found timeout mail with code: '$code'"));
      }
      
      return success("Successfull.");
    }


    public static function removeCodesFor (int $userID): Result {
      if ($userID < 1) {
        return fail(new InvalidArgumentExc("User ID is not defined."));
      }

      $mails = self::parseProps(Database::get()->fetchAll(
        "SELECT
          `timeoutmails`.`ID` ID
        FROM
          `timeoutmails`
          JOIN verificationcodes AS vc ON (timeoutmails.ID, timeoutmails.usersID) = (vc.verificationCodesID, :userID)",
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






    






    
    private const URL_ARG_CHARS = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_";
    public static function genUrlArg (): string {
      return join(
        "",
        array_map(function ($v) {
          return self::URL_ARG_CHARS[random_int(0, 63)];
        }, array_fill(
          0,
          32,
          null
        ))
      );
    }


    public static function isUrlArgTaken (string $urlArg): Result {
      if ($urlArg == "") {
        return fail(new InvalidArgumentExc("Url argument is not defined."));
      }

      return success(Count::parseProps(Database::get()->fetch(
        "SELECT COUNT(*) amount
        FROM `passwordrecoveries`
        WHERE urlArg = :urlArg",
        Count::class,
        [new DatabaseParam("urlArg", $urlArg, PDO::PARAM_STR)]
      ))->amount != 0);
    }


    public static function insertUrlArg (string $urlArg, int $userID): Result {
      if ($urlArg == "") {
        return fail(new InvalidArgumentExc("Url argument is not defined."));
      }

      $sideEffect = self::insert($userID);
      $timeoutMailID = $sideEffect->lastInsertedID;
      
      Database::get()->statement(
        "INSERT INTO `passwordrecoveries`(`passwordRecoveriesID`, `urlArg`)
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
          users.ID ID,
          users.profileSRC profileSRC,
          users.email email,
          users.password \"password\",
          users.level \"level\",
          users.website website,
          timeoutmails.expires as expires
        FROM
          `timeoutmails`
          JOIN passwordrecoveries as pr ON pr.passwordRecoveriesID = timeoutmails.ID
            AND pr.urlArg = :urlArg
            AND timeoutmails.expires - UNIX_TIMESTAMP() >= 0
          JOIN users ON users.ID = timeoutmails.usersID",
        stdClass::class,
        [new DatabaseParam("urlArg", $urlArg, PDO::PARAM_STR)]
      );

      if ($optionalUser == false) {
        return fail(new NotFoundExc("Could not found user."));
      }

      if (intval($optionalUser->expires) - time() < 0) {
        return fail(new InvalidArgumentExc("Url argument has expired."));
      }

      return success($optionalUser);
    }


    public static function removeUA (string $ua): Result {
      if ($ua == "") {
        return fail(new InvalidArgumentExc("Url argument is not defined"));
      }

      $mail = self::parseProps(Database::get()->fetch(
        "SELECT passwordRecoveriesID
        FROM `passwordrecoveries`
        WHERE urlArg = :ua",
        self::class,
        [new DatabaseParam("ua", $ua, PDO::PARAM_STR)]
      ));

      $sideEffect = Database::get()->statement(
        "DELETE FROM timeoutmails
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
          `timeoutmails`.`ID` ID
        FROM
          `timeoutmails`
          JOIN passwordrecoveries AS pr ON (timeoutmails.ID, timeoutmails.usersID) = (pr.passwordRecoveriesID, :userID)",
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