<?php

  require_once __DIR__ . "/../../models/User.php";
  require_once __DIR__ . "/../../lib/susmail/susmail.php";
  require_once __DIR__ . "/../../lib/retval/retval.php";
  require_once __DIR__ . "/../../models/TimeoutMail.php";
  require_once __DIR__ . "/../../lib/rekves/rekves.php";

  $userRes = User::getByEmail($req->body->email);
  $userRes->forwardFailure($res);

  $arg = null;
  $userRes->succeeded(function ($user) use ($res) {
    TimeoutMail::removeUAsFor($user->ID);

    global $arg;
    if ($user->email == null) {
      $res->json(new NotFoundExc("Could not find user with this email."));
      return;
    }

    while ($arg == null) {
      $generated = TimeoutMail::genUrlArg();
      $argRes = TimeoutMail::isUrlArgTaken($generated);
      $argRes->succeeded(function ($isTaken) use ($generated) {
        global $arg;
        if ($isTaken == false) {
          $arg = $generated;
        }
      });      
    }

    $insertRes = TimeoutMail::insertUrlArg($arg, $user->ID);
    $insertRes->forwardFailure($res);

    MailTemplate::passwordRecovery($user, $arg)->send();

    $res->json((object)["next" => "forgotten-password-2"]);
  });