<?php

  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../lib/rekves/rekves.php";
  require_once __DIR__ . "/../models/TimeoutMail.php";

  $userRes = TimeoutMail::getUserWithUA($req->body->get("prid", ""));
  $userRes->succeeded(function ($user) use ($req) {
    $req->session->prUser = $user;
  });

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <link rel="stylesheet" href="../public/css/main.css">
  <link rel="stylesheet" href="../public/css/login-register.css">

  <script src="../public/js/main.js"></script>
  <script src="../public/js/forms.js" defer></script>
  <script src="../public/js/password-recovery.js" defer></script>
</head>
<body class="center">
  <p class="PHP-Exception"></p>

  <?php if ($userRes->isSuccess()) { ?>
    <div class="form new-password">
      <div class="wrapper">
        <p class="label">New password:</p>
        <input type="password" id="n-password" name="n-password">
        <p class="blockquote error"></p>
      </div>

      <div class="wrapper">
        <p class="label">New password again:</p>
        <input type="password" id="n-password-again" name="n-password-again">
      </div>

      <div class="divider"></div>

      <div class="wrapper">
        <button class="submit">Submit</button>
      </div>

      <div class="wrapper">
        <p class="blockquote error"></p>
      </div>

      <div class="hline"></div>

      <div class="wrapper">
        <p>Back to <a href="./login-register.php">login.</a></p>
      </div>
    </div>

    <div class="form success hide">
      <div class="wrapper">
        <p>Your password has been reset. You may <a href="./login-register.php">login here</a> with your new password.</p>
      </div>
    </div>
  <?php } else { ?>
    <?php
      $failure = $userRes->getFailure();
      if ($failure instanceof InvalidArgumentExc) {
    ?>
      <div class="form expired">
        <div class="wrapper">
          <p class="blockquote note show">Your link has expired. You may request new link <a href="./login-register.php">here.</a></p>
        </div>
      </div>
    <?php } else { ?>
      <div class="form expired">
        <div class="wrapper">
          <p class="blockquote error show">Your link is not valid. You may request new link <a href="./login-register.php">here.</a></p>
        </div>
      </div>
    <?php } ?>
  <?php } ?>
</body>
</html>