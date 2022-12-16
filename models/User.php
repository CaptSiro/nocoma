<?php

  require_once __DIR__ . "/../lib/modelist/modelist.php";
  require_once __DIR__ . "/Count.php";
  require_once __DIR__ . "/../lib/retval/retval.php";

  class User extends StrictModel {
    public $ID, $themesID, $profileSRC, $email, $password, $username, $level, $website, $isVerified;
    const ALL_COLUMNS = ["ID", "themesID", "profileSRC", "email", "password", "username", "level", "website", "isVerified"];

    public function comparePassword (string $password): Result {
      if ($password == "") {
        return fail(new InvalidArgumentExc("Password is not defined"));
      }

      return success(password_verify($password, $this->password));
    }






    protected static function getNumberProps (): array {
      return ["ID", "themesID", "level"];
    }


    protected static function getBooleanProps (): array { return ["isVerified"]; }







    static function get (int $userID): Result {
      $optionalUser = Database::get()->fetch(
        "SELECT
          " . self::generateSelectColumns("users", self::ALL_COLUMNS) . "
        FROM users
        WHERE users.ID = :userID",
        self::class,
        [new DatabaseParam("userID", $userID)]
      );

      if ($optionalUser === false) {
        return fail(new NotFoundExc("Could not found user."));
      }

      return success(self::parseProps($optionalUser));
    }


    static function getByEmail (string $email): Result {
      if ($email == "") {
        return fail(new InvalidArgumentExc("Email is not defined"));
      }

      $optUser = Database::get()->fetch(
        "SELECT
          " . self::generateSelectColumns("users", self::ALL_COLUMNS) . "
        FROM users
        WHERE users.email = :email",
        self::class,
        [new DatabaseParam("email", $email, PDO::PARAM_STR)]
      );
      
      if ($optUser === false) {
        return fail(new NotFoundExc("Could not find user."));
      }

      return success(self::parseProps($optUser));
    }
    
    static function getByWebsite (string $website): Result {
      if ($website == "") {
        return fail(new InvalidArgumentExc("Website is not defined"));
      }
  
      $optionalUser = Database::get()->fetch(
        "SELECT
          " . self::generateSelectColumns("users", self::ALL_COLUMNS) . "
        FROM users
        WHERE users.website = :website",
        self::class,
        [new DatabaseParam("website", $website, PDO::PARAM_STR)]
      );
  
      if ($optionalUser === false) {
        return fail(new NotFoundExc("Could not find user."));
      }
  
      return success(self::parseProps($optionalUser));
    }
  
  
    /**
     * @param string $email
     * @return Result<bool|null>
     */
    static function isEmailTaken (string $email): Result {
      if ($email == "") {
        return fail(new InvalidArgumentExc("Email is not defined"));
      }

      return success((Count::parseProps(Database::get()->fetch(
        "SELECT
          COUNT(*) amount
        FROM users
        WHERE users.email = :email",
        Count::class,
        [new DatabaseParam("email", $email, PDO::PARAM_STR)]
      )))->amount != 0);
    }


    static function isWebsiteTaken (string $website): Result {
      if ($website == "") {
        return fail(new InvalidArgumentExc("Website is not defined"));
      }

      return success((Count::parseProps(Database::get()->fetch(
        "SELECT
          COUNT(*) amount
        FROM users
        WHERE LOWER(users.website) = LOWER(:website)",
        Count::class,
        [new DatabaseParam("website", $website, PDO::PARAM_STR)]
      )))->amount != 0);
    }






    static function register (string $email, string $username, string $website, string $password): SideEffect {
      return Database::get()->statement(
        "INSERT INTO `users`(`themesID`, `email`, `username`, `password`, `level`, `website`, `isVerified`)
        VALUES (1, :email, :username, :password, 1, :website, 0)",
        [
          new DatabaseParam("email", $email, PDO::PARAM_STR),
          new DatabaseParam("username", $username, PDO::PARAM_STR),
          new DatabaseParam("website", $website, PDO::PARAM_STR),
          new DatabaseParam("password", $password, PDO::PARAM_STR),
        ]
      );
    }


    static function verify (int $userID) {
      Database::get()->statement(
        "UPDATE `users`
        SET `isVerified` = 1
        WHERE ID = :userID",
        [new DatabaseParam("userID", $userID)]
      );
    }


    static function updatePassword (string $hashedPassword, int $userID) {
      Database::get()->statement(
        "UPDATE `users`
        SET `password` = :newPassword
        WHERE ID = :userID",
        [
          new DatabaseParam("newPassword", $hashedPassword, PDO::PARAM_STR),
          new DatabaseParam("userID", $userID),
        ]
      );
    }
  }