<?php

  require_once __DIR__ . "/../dotenv/dotenv.php";
  require_once __DIR__ . "/../paths.php";
  require_once __DIR__ . "/../retval/retval.php";
  $__dotEnv = new Env(ENV_FILE);

  class Path {
    /**
     * Translates path that is relative to project-manager directory to current working directory 
     */
    static function translate (string $p): Result {
      global $__dotEnv;

      $cwd = getcwd();
      $cwdCount = count(explode(
        (strpos($cwd, "/") !== false)
          ? "/"
          : "\\",
        $cwd
      ));
  
      // relative path instead of absolute
      $root = $_SERVER["DOCUMENT_ROOT"];
      $rootDirRes = $__dotEnv->get("ROOT_DIR");
      if ($rootDirRes->isFailure()) {
        return $rootDirRes;
      }

      $projectDirCount = count(explode("/", $root . $rootDirRes->getSuccess()));
  
      return success(str_repeat("../", $cwdCount - $projectDirCount) . $p);
    }

    static function breakdown (string $path): array {
      $separator = (strpos($path, "/") !== false)
        ? "/"
        : "\\";

      return explode($separator, $path);
    }
  }