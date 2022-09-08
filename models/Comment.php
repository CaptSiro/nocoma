<?php

  require_once(Path::translate("./lib/autrack/autrack.php"));

  class Comment extends StrictModel {
    protected $ID, $websitesID, $parentCommentID, $timePosted, $content, $score;

    protected static function getNumberProps (): array { return ["ID", "websiteID", "parentCommentID", "score"]; }
    protected static function getBooleanProps (): array { return []; }
  }