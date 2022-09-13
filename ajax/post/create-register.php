<?php

  require_once __DIR__ . "/../../models/User.php";
  require_once __DIR__ . "/../../lib/rekves/rekves.php";

  $emailRegex = "/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/";
  $websiteRegex = "/^[a-zA-Z0-9-_]{1,64}$/";
  $passwordRegex = "/(?=.{8,})(?=.*[a-zA-Z]+)(?=.*[0-9]+)(?=.*[%_&@]+)^[a-zA-Z0-9&%@_]+$/";

  if (!preg_match($emailRegex, $req->body->email)) {
    $res->json((object)["error" => "Not a valid email."]);
  }
  
  if (!preg_match($websiteRegex, $req->body->website)) {
    $res->json((object)["error" => "Not a valid website."]);
  }
  
  if (!preg_match($passwordRegex, $req->body->password)) {
    $res->json((object)["error" => "Password is not strong enough."]);
  }

  $emailRes = User::isEmailTaken($req->body->email);
  $websiteRes = User::isWebsiteTaken($req->body->website);

  $emailRes->forwardFailure($res);
  $websiteRes->forwardFailure($res);

  $isTakenFN = function ($prop) use ($res) {
    return function ($isTaken) use ($res, $prop) {
      if ($isTaken == true) {
        $res->json((object)["error" => "$prop is taken."]);
      }
    };
  };

  $emailRes->succeeded($isTakenFN("Email"));
  $websiteRes->succeeded($isTakenFN("Website"));

  // all good, register user

  $sideEffect = User::register($req->body->email, $req->body->website, password_hash($req->body->password, PASSWORD_DEFAULT));
  $userRes = User::get($sideEffect->lastInsertedID);

  $userRes->either(function ($user) use ($req) {
    $req->session->user = $user;
  }, function ($exc) use ($res) {
    $res->json($exc);
  });

  $res->json((object)["next" => "code-verification"]);