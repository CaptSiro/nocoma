<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="title" content="<?= htmlspecialchars($GLOBALS["webpage"]->title) ?>">
  <title><?= htmlspecialchars($GLOBALS["webpage"]->title) ?></title>
  
  <link rel="icon" href="<?= $GLOBALS["__HOME__"] ?>/public/images/nocoma-icon.ico">
  
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/js/main-v2.js"></script>
  <script>
    AJAX.DOMAIN_HOME = "<?=$GLOBALS["__HOME__"]?>";
    AJAX.SERVER_HOME = "<?=$GLOBALS["__SERVER_HOME__"] ?? $GLOBALS["__HOME__"]?>";
    
    const webpage = Object.freeze(JSON.parse(`<?= json_encode($GLOBALS["webpage"]) ?>`));
    const user = Object.freeze(JSON.parse(`<?= json_encode($GLOBALS["user"]) ?>`));
  </script>
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/js/theme.js"></script>
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/js/widget-core.js"></script>
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/js/components/InfiniteScroller.js"></script>
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/bundler/js/?widgets=WRoot" id="widgets-scripts"></script>
  <link rel="stylesheet" href="<?= $GLOBALS["__SERVER_HOME__"] ?>/bundler/css/?widgets=WRoot" id="widgets-styles">
  
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/js/shell.js" defer></script>
  
  <link rel="stylesheet" href="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/css/main.css">
  
  <style>
    .notice-box {
      position: fixed;
      width: 100%;
      top: 0;
      left: 0;
      z-index: 100;
    }

    .notice-box p.blockquote.note {
      margin-top: 0;
    }
  </style>
</head>
<body id="viewport">
  <?php if (isset($GLOBALS["notice"])) { ?>
    <div class="notice-box">
      <p class="blockquote note"><?= $GLOBALS["notice"] ?></p>
    </div>
  <?php } ?>
  <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: none;">
    <defs id="icon-definitions"></defs>
  </svg>
  <div id="page-data" class="display-none viewport-tablet viewport-smartphone viewport-small-smartphone">