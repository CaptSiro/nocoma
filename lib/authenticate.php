<?php

  require_once __DIR__ . "/rekves/rekves.php";
  require_once __DIR__ . "/retval/retval.php";

  const AUTH_LOGGED_IN = -1;
  const AUTH_NOT_LOGGED_IN = -2;

  // possible user levels
  const AUTH_ADMIN = 0;
  const AUTH_USER = 1;

  const AUTH_DEFAULT_REDIRECT_MAP = [
    AUTH_ADMIN => "./admin-dashboard.php",
    AUTH_USER => "./user-dashboard"
  ];



  class AuthResult {
    public $passed, $redirect;

    public function __construct (bool $passed, string $redirect = "") {
      $this->passed = $passed;
      $this->redirect = $redirect;
    }
  }

  class Auth {
    private static function authenticate (?User $user, array $requiredLevels, array $redirectMap): Result {
      if (count($requiredLevels) == 0) {
        return fail(new InvalidArgumentExc("Unaccessable website!"));
      }

      if ($user === null) {
        if ($requiredLevels[0] === AUTH_NOT_LOGGED_IN) {
          return success(new AuthResult(true));
        }
  
        return success(new AuthResult(false, "./login-register.php"));
      } else {
        if ($requiredLevels[0] === AUTH_LOGGED_IN) {
          return success(new AuthResult(true));
        }
  
        $currentLevel = $user->level;
        if (in_array($currentLevel, $requiredLevels)) {
          return success(new AuthResult(true));
        }
  
        return success(new AuthResult(false, $redirectMap[$currentLevel]));
      }
    }

    public static function redirect (?User $user, array $requiredLevels, array $redirectMap): void {
      $authRes = self::authenticate($user, $requiredLevels, $redirectMap);

      global $res;
      $authRes->forwardFailure($res);

      $page = $authRes->getSuccess()->redirect;
      if ($page == "") return;

      $res->setHeader("Location", $page);
      $res->send("");
    }

    public static function boolean (?User $user, array $requiredLevels, array $redirectMap): bool {
      $authRes = self::authenticate($user, $requiredLevels, $redirectMap);

      if ($authRes->isFailure()) {
        exit("Failed to authenticate: " . $authRes->getFailure()->getMessage());
      }

      return $authRes->getSuccess()->passed;
    }
  }