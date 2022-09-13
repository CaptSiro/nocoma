<?php

  require_once __DIR__ . "/../lib/modelist/modelist.php";

  class Media extends StrictModel {
    protected $src, $extension, $basename;

    protected static function getNumberProps (): array { return []; }
    protected static function getBooleanProps (): array { return []; }
  }