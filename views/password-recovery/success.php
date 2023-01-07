<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/main.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/forms.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/login-register.css">
  
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/main-v2.js"></script>
  <script>
    AJAX.DOMAIN_HOME = "<?=$GLOBALS["__HOME__"]?>";
    AJAX.SERVER_HOME = "<?=$GLOBALS["SERVER_HOME"] ?? $GLOBALS["__HOME__"]?>";
  </script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/background-loader.js"></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/forms.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/password-recovery.js" defer></script>
</head>
<body class="center">
  <p class="PHP-Exception"></p>
    <div class="form new-password">
      <div class="wrapper">
        <p class="label">New password:</p>
        <input type="password" id="new-password" name="new-password">
        <p class="blockquote error"></p>
      </div>
      
      <div class="wrapper">
        <p class="label">New password again:</p>
        <input type="password" id="new-password-again" name="new-password-again">
      </div>
      
      <div class="divider"></div>
      
      <div class="wrapper">
        <button class="submit">Submit</button>
      </div>
      
      <div class="wrapper">
        <p class="blockquote error" id="password-recovery-error"></p>
      </div>
      
      <div class="hline"></div>
      
      <div class="wrapper">
        <p>Back to <a href="<?= $GLOBALS["replenishLink"] ?>">login.</a></p>
      </div>
    </div>
    
    <div class="form success hide">
      <div class="wrapper">
        <?php if ($GLOBALS["displayDashboardMessage"]) { ?>
          <p>Your password has been reset.<br><br>You may close this page and return to <a href="<?= $GLOBALS["__SERVER_HOME__"] ?>/dashboard">dashboard</a>.</p>
        <?php } else { ?>
          <p>Your password has been reset.<br><br>You may <a href="<?= $GLOBALS["replenishLink"] ?>">login here</a> with your new password.</p>
        <?php } ?>
      </div>
    </div>
</body>
</html>