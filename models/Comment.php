<?php

  require_once(__DIR__ . "/../lib/modelist/modelist.php");

  class Comment extends StrictModel {
    protected $ID, $websitesID, $parentCommentID, $timePosted, $content;

    protected static function getNumberProps (): array { return ["ID", "websiteID", "parentCommentID", "score"]; }
    protected static function getBooleanProps (): array { return []; }
  }