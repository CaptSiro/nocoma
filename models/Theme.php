<?php

  require_once __DIR__ . "/../lib/modelist/modelist.php";

  class Theme extends StrictModel {
    protected $ID, $name;

    protected static function getNumberProps (): array { return ["ID"]; }
    protected static function getBooleanProps (): array { return []; }
  }