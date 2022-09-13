<?php

  require_once __DIR__ . "/../lib/modelist/modelist.php";

  class Website extends StrictModel {
    protected $ID, $usersID, $thumbnailSRC, $timeCreated, $title, $description, $templateStyle, $isPublic, $areCommentsEnabled, $isHomepage;

    protected static function getNumberProps (): array { return ["ID", "userID", "templateStyle"]; }
    protected static function getBooleanProps (): array { return ["isPublic", "areCommentsEnabled", "isHomepage"]; }
  }