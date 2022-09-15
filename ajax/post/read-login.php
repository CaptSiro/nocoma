<?php

  require_once __DIR__ . "/../../models/User.php";
  require_once __DIR__ . "/../../lib/rekves/rekves.php";

  $userRes = User::getByEmail($req->body->email);
  $userRes->failed(function ($ignored) use ($res) {
    $res->json((object)["error" => "Email or password does not match. (2)"]);
  });

  $user = $userRes->getSuccess();
  $cmpRes = $user->comparePassword($req->body->password);

  $cmpRes->forwardFailure($res);
  $cmpRes->succeeded(function ($isSame) use ($req, $res, $user) {
    if ($isSame === true) {
      $req->session->user = $user;

      if ($user->isVerified == false) {
        $res->json((object)["next" => "code-verification"]);
      }

      $res->json((object)["redirect" => "./user-dashboard.php"]);
    } else {
      $res->json((object)["error" => "Email or password does not match."]);
    }
  });