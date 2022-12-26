<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($GLOBALS["webpage"]->title) ?></title>
  
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/js/main.js"></script>
  <script>
    AJAX.DOMAIN_HOME = "<?=$GLOBALS["__HOME__"]?>";
    AJAX.SERVER_HOME = "<?=$GLOBALS["__SERVER_HOME__"] ?? $GLOBALS["__HOME__"]?>";
  </script>
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/js/widget-core.js"></script>
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/widgets/root/root.js"></script>
  
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/js/shell.js" defer></script>
  
  <link rel="stylesheet" href="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/css/main.css">
  
  <style>
      body {
          background-color: #BA9673;
      }

      .display-none {
          display: none;
      }
  </style>
</head>
<body>
  <div id="page-data" class="display-none viewport-tablet viewport-smartphone viewport-small-smartphone">