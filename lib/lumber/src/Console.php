<?php

  class Console {
    static function log ($content, $__file__ = null, $__line__ = null, $f = "logs.txt", $dir = null) {
      $dir = ($dir == null)
        ? __DIR__
        : $dir;

      $path = $dir . "\\" . $f;
      $log = (isset($__file__) ? "$__file__:($__line__) " : "") . $content . "\n";

      $temp = file_get_contents($path);
      file_put_contents($path, $log . $temp);
    }

    static function print ($object, $__file__ = null, $__line__ = null, $f = "logs.txt", $dir = null) {
      self::log(print_r($object, true), $__file__, $__line__, $f, $dir);
    }

    static function header ($header, $content, $f = "log.txt", $dir = null) {
      self::log("$header: $content", null, null, $f, $dir);
    }

    static function date ($content, $f = "log.txt", $dir = null) {
      self::log(date("Y-m-d H:i:s") . ": $content", null, null, $f, $dir);
    }
  }