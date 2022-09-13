<?php

  require_once __DIR__ . "/../../models/User.php";
  require_once __DIR__ . "/../../lib/retval/retval.php";
  require_once __DIR__ . "/../../models/TimeoutMail.php";
  require_once __DIR__ . "/../../lib/susmail/susmail.php";
  require_once __DIR__ . "/../../lib/rekves/rekves.php";

  $userRes = User::get(intval($req->session->get("user")->ID));
  $userRes->forwardFailure($res);
  
  $user = $userRes->getSuccess();

  $code = null;
  while ($code == null) {
    $generated = TimeoutMail::genCode();
    $result = TimeoutMail::isCodeTaken($generated);
    $result->succeeded(function ($isTaken) use ($generated) {
      global $code;
      if ($isTaken == false) {
        $code = $generated;
      }
    });
  }

  TimeoutMail::insertCode($code, $user->ID);
  MailTemplate::verifyAccount($user, $code)->send();

  $res->json((object)["msg" => "code sent"]);