<?php

  require_once __DIR__ . "/../../models/User.php";
  require_once __DIR__ . "/../../models/TimeoutMail.php";
  require_once __DIR__ . "/../../lib/retval/retval.php";
  require_once __DIR__ . "/../../lib/rekves/rekves.php";

  $passwordRegex = "/(?=.{8,})(?=.*[a-zA-Z]+)(?=.*[0-9]+)(?=.*[%_&@]+)^[a-zA-Z0-9&%@_]+$/";

  if (!preg_match($passwordRegex, $req->body->password)) {
    $res->json(new InvalidArgumentExc("Password is not strong enough."));
  }

  if (!$req->session->isset("prUser")) {
    $res->json(new InvalidArgumentExc("Not known user has requested password recovery"));
  }

  TimeoutMail::removeUA($req->body->urlArg);
  User::updatePassword(
    password_hash($req->body->password, PASSWORD_DEFAULT),
    $req->session->prUser->ID
  );

  $req->session->unset("prUser");

  $res->json((object)["next" => "success"]);