<?php

  require_once __DIR__ . "/rekves.php";

  const LEVEL_ANONYMOUS = -1;
  const LEVEL_ADMIN = 0;
  const LEVEL_USER = 1;

  function authMiddleware ($requiredLevel) {
    global $res;
    global $req;

    if ($req->session->get("user") == null) {
      if ($requiredLevel !== LEVEL_ANONYMOUS) {
        $res->setHeader("Location", "/login-register.php");
        $res->send("");
      }
    } else {
      if ($requiredLevel !== $req->session->user->level && $requiredLevel == LEVEL_ADMIN) {

      }

      if ($requiredLevel !== $req->session->user->level && $requiredLevel == LEVEL_USER) {
        
      }
    }
  }