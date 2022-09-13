<?php

  require_once(__DIR__ . "/../lib/modelist/modelist.php");

  class Count extends StrictModel {
    protected $amount;

    protected static function getNumberProps (): array { return ["amount"]; }
    protected static function getBooleanProps (): array { return []; }
  }