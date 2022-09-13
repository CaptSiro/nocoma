<?php

  require_once __DIR__ . "/../../models/User.php";
  require_once __DIR__ . "/../../lib/retval/retval.php";
  require_once __DIR__ . "/../../models/TimeoutMail.php";
  require_once __DIR__ . "/../../lib/rekves/rekves.php";

  $userRes = TimeoutMail::getUserWithCode($req->body->code);
  $userRes->forwardFailure($res);

  $user = $userRes->getSuccess();
  User::verify($user->ID);

  $res->json((object)["redirect" => "./user-dashboard.php"]);