<?php

  class Console {
    static function log ($content, $f = "log.txt", $dir = null) {
      $dir = ($dir == null)
        ? __DIR__
        : $dir;

      $path = $dir . "/" . $f;

      $temp = file_get_contents($path);
      file_put_contents($path, $content . "\n" . $temp);
    }

    static function print ($object, $f = "log.txt", $dir = null) {
      self::log(print_r($object, true), $f, $dir);
    }

    static function header ($header, $content, $f = "log.txt", $dir = null) {
      self::log("$header: $content", null, null, $f, $dir);
    }

    static function date ($content, $f = "log.txt", $dir = null) {
      self::log(date("Y-m-d H:i:s") . ": $content", null, null, $f, $dir);
    }

    static function debug ($content, $f = "log.txt", $dir = null) {
      $trace = debug_backtrace()[0];

      self::log($trace["file"] . "(" . $trace["line"] . "): $content\n", $f, $dir);
    }
  }