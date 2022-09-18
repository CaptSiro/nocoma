<?php
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../lib/rekves/rekves.php";
  require_once __DIR__ . "/../lib/src/authenticate.php";

  Auth::redirect($req->session->get("user"), [AUTH_ADMIN], AUTH_DEFAULT_REDIRECT_MAP);
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - NoComa</title>
</head>
<body>
  <p>LETS START THE BAN-FEST😈, shall we?</p>
  <a href="../ajax/get/delete-logout.php">Logout</a>
</body>
</html>