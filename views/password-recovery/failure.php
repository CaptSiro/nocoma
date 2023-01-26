<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Password recovery failure - Nocoma</title>
  
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/main.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/forms.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/login-register.css">
  
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/main-v2.js"></script>
  <script>
    AJAX.DOMAIN_HOME = "<?=$GLOBALS["__HOME__"]?>";
    AJAX.SERVER_HOME = "<?=$GLOBALS["__SERVER_HOME__"] ?? $GLOBALS["__HOME__"]?>";
  </script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/background-loader.js"></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/forms.js" defer></script>
</head>
<body class="center">
  <div class="form expired">
    <div class="wrapper">
      <p class="blockquote error show">Access denied.</p>
    </div>
    <div class="wrapper">
      <p class="blockquote note show"><?= $GLOBALS["message"] ?> You may request new link <a href="<?= $GLOBALS["replenishLink"] ?>">here.</a></p>
    </div>
  </div>
</body>
</html>