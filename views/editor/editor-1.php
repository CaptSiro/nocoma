
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($GLOBALS["webpage"]->title) ?> - Nocoma editor</title>
  
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/main-v2.js"></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/custom.js"></script>
  <script>
    AJAX.DOMAIN_HOME = "<?=$GLOBALS["__HOME__"]?>";
    AJAX.SERVER_HOME = "<?=$GLOBALS["__SERVER_HOME__"] ?? $GLOBALS["__HOME__"]?>";

    const webpage = Object.freeze(JSON.parse(`<?= json_encode($GLOBALS["webpage"]) ?>`));
    const user = Object.freeze(JSON.parse(`<?= json_encode($GLOBALS["user"]) ?>`));
  </script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/editor.js" defer></script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/widget-core.js"></script>
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/js/components/InfiniteScroller.js"></script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/bundler/js/?widgets=*" id="widgets-scripts"></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/modal.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/forms.js" defer></script>
  
  <link rel="stylesheet" href="<?= $GLOBALS["__HOME__"] ?>/bundler/css/?widgets=*" id="widgets-styles">
  <link rel="stylesheet" href="<?= $GLOBALS["__HOME__"] ?>/public/css/main.css">
  <link rel="stylesheet" href="<?= $GLOBALS["__HOME__"] ?>/public/css/editor.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/modal.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/forms.css">
  
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
  <div class="modals">
    <div class="window form large" id="file-select" data-multiple="false" data-fileType="theme">
      <div class="wrapper row">
        <p class="label">Select uploaded file:</p>
        <label for="file-upload-input" class="button-like-main">Upload new</label>
        <input type="file" id="file-upload-input" class="display-none" multiple>
      </div>
      
      <div class="wrapper files"></div>
      
      <div class="divider"></div>
  
      <div class="wrapper sideways-end">
        <button class="cancel-modal">Cancel</button>
        <button class="submit" type="submit">Submit</button>
      </div>
  
      <div class="wrapper">
        <p class="blockquote error error-modal"></p>
      </div>
    </div>
  </div>
  <div class="display-none" id="page-data">