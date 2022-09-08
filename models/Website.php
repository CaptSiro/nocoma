<?php

  require_once(Path::translate("./lib/autrack/autrack.php"));

  class Website extends StrictModel {
    protected $ID, $usersID, $thumbnailSRC, $timeCreated, $title, $description, $isPublic, $templateStyle, $areCommentsEnabled;

    protected static function getNumberProps (): array { return ["ID", "userID", "templateStyle"]; }
    protected static function getBooleanProps (): array { return ["isPublic", "areCommentsEnabled"]; }
  }