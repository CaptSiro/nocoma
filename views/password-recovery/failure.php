<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Password recovery failure - Nocoma</title>
  
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/main.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/login-register.css">
  
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/main.js"></script>
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