
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($GLOBALS["webpage"]->title) ?> - Nocoma editor</title>
  
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/main-v2.js"></script>
  <script>
    AJAX.DOMAIN_HOME = "<?=$GLOBALS["__HOME__"]?>";
    AJAX.SERVER_HOME = "<?=$GLOBALS["SERVER_HOME"] ?? $GLOBALS["__HOME__"]?>";
  </script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/editor.js" defer></script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/widget-core.js"></script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/bundler/js/*" id="widgets-scripts"></script>
  <link rel="stylesheet" href="<?= $GLOBALS["__HOME__"] ?>/bundler/css/*" id="widgets-styles">
  
  <link rel="stylesheet" href="<?= $GLOBALS["__HOME__"] ?>/public/css/main.css">
  <link rel="stylesheet" href="<?= $GLOBALS["__HOME__"] ?>/public/css/editor.css">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
  
  <style>
      .display-none {
          display: none;
      }
  </style>
</head>
<body>
  <div class="display-none" id="page-data">