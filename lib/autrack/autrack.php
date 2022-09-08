<?php
  class Path {
    /**
     * Translates path that is relative to projects in www directory to current working directory 
     */
    static function translate (string $p): string {
      $cwd = getcwd();
      $cwdCount = count(explode(
        (strpos($cwd, "/") !== false)
          ? "/"
          : "\\",
        $cwd
      ));

      // relative path instead of absolute
      $projectDirCount = (isset($autrackOptions["root"])) 
        ? count(explode(
            (strpos($autrackOptions["root"], "/") !== false)
              ? "/"
              : "\\",
            $autrackOptions["root"]
          ))
        : count(explode("/", $_SERVER["DOCUMENT_ROOT"])) + 1;

      return str_repeat("../", $cwdCount - $projectDirCount) . $p;
    }

    static function breakdown (string $path): array {
      $separator = (strpos($path, "/") !== false)
        ? "/"
        : "\\";

      return explode($separator, $path);
    }
  }