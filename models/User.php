<?php

  require_once(Path::translate("./lib/autrack/autrack.php"));

  class User extends StrictModel {
    protected $ID, $profileSRC, $username, $password, $level, $website;

    protected static function getNumberProps (): array {
      return ["ID", "level"];
    }
    protected static function getBooleanProps (): array { return []; }
  }