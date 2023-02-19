<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Error - <?= $GLOBALS["message"] ?></title>
  
  <script src="<?=$GLOBALS["__SERVER_HOME__"]?>/public/js/main-v2.js"></script>
  <script>
    AJAX.DOMAIN_HOME = "<?=$GLOBALS["__HOME__"]?>";
    AJAX.SERVER_HOME = "<?=$GLOBALS["__SERVER_HOME__"] ?? $GLOBALS["__HOME__"]?>";
  </script>
  <script src="<?=$GLOBALS["__SERVER_HOME__"]?>/public/js/background-loader.js"></script>
  <link rel="stylesheet" href="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/css/main.css">
  
  <link rel="icon" href="<?= $GLOBALS["__HOME__"] ?>/public/images/nocoma-icon.ico">
  
  <style>
    body {
      width: 100vw;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: var(--container-opposite-3);
      background-size: cover;
    }
    
    div.default-container {
      width: 50vw;
    }
    
    p {
      margin: unset !important;
      overflow: hidden;
    }
  </style>
</head>
<body>
  <div class="default-container">
    <p class="blockquote error show"><?= $GLOBALS["message"] ?></p>
  </div>
</body>
</html>