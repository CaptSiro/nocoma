<?php

  require_once __DIR__ . "/../../lib/rekves/rekves.php";

  $res->json(
    array_values(
      array_diff(
        scandir(__DIR__ . "/../../public\images\login-register-bgs"), 
        array('.', '..')
      )
    )
  );