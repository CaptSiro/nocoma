<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Error - <?= $GLOBALS["message"] ?></title>
  
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/main.js"></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/background-loader.js"></script>
  <link rel="stylesheet" href="<?= $GLOBALS["__HOME__"] ?>/public/css/main.css">
  
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