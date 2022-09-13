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
          new DatabaseParam("code", $code)
        ]
      );

      return success($timeoutMailID);
    }

    public static function getUserWithCode (string $code): Result {
      self::purgeOld();

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
          users.website website
        FROM
          `timeoutmails`
          JOIN verificationcodes as vc ON vc.verificationCodesID = timeoutmails.ID
            AND vc.code = :code
            AND timeoutmails.expires - UNIX_TIMESTAMP() >= 0
          JOIN users ON users.ID = timeoutmails.usersID",
        User::class,
        [new DatabaseParam("code", $code, PDO::PARAM_STR)]
      );

      if ($optionalUser == false) {
        return fail(new NotFoundExc("Could not found user."));
      }

      return success($optionalUser);
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
          new DatabaseParam("urlArg", $urlArg)
        ]
      );

      return success($timeoutMailID);
    }

    public static function getUserWithUA (string $urlArg): Result {
      self::purgeOld();

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
          users.website website
        FROM
          `timeoutmails`
          JOIN passwordrecoveries as pr ON pr.passwordRecoveriesID = timeoutmails.ID
            AND pr.urlArg = :urlArg
            AND timeoutmails.expires - UNIX_TIMESTAMP() >= 0
          JOIN users ON users.ID = timeoutmails.usersID",
        User::class,
        [new DatabaseParam("urlArg", $urlArg, PDO::PARAM_STR)]
      );

      if ($optionalUser == false) {
        return fail(new NotFoundExc("Could not found user."));
      }

      return success($optionalUser);
    }
  }