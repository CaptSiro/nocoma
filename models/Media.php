<?php

  require_once(Path::translate("./lib/autrack/autrack.php"));

  class Media extends StrictModel {
    protected $src, $extension, $basename;

    protected static function getNumberProps (): array { return []; }
    protected static function getBooleanProps (): array { return []; }
  }