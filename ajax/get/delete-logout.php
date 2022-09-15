<?php

  require_once __DIR__ . "/../../lib/rekves/rekves.php";

  $req->session->unset("user");

  $res->setHeader("Location", "/nocoma");
  $res->generateHeaders();