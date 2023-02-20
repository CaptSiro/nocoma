
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
    const postLink = `<?= $GLOBALS["postLink"] ?>`;
  </script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/theme.js"></script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/editor.js" defer></script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/widget-core.js"></script>
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/js/components/InfiniteScroller.js"></script>
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/js/components/Resizeable.js"></script>
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/js/components/FireAble.js"></script>
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/js/components/SequentialAnimation.js"></script>
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/js/components/TextSlider.js"></script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/bundler/js/?widgets=*" id="widgets-scripts"></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/modal.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/forms.js" defer></script>
  
  <link rel="stylesheet" href="<?= $GLOBALS["__HOME__"] ?>/bundler/css/?widgets=*" id="widgets-styles">
  <link rel="stylesheet" href="<?= $GLOBALS["__HOME__"] ?>/public/css/main.css">
  <link rel="stylesheet" href="<?= $GLOBALS["__HOME__"] ?>/public/css/editor.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/modal.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/forms.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/resizeable.css">
  
  <link rel="icon" href="<?= $GLOBALS["__HOME__"] ?>/public/images/nocoma-icon.ico">
  
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
  <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: none;">
    <defs id="icon-definitions">
      <g id="icon-arrow">
        <g>
          <polygon fill="currentColor" points="411.8,250.1 87.7,450.4 87.7,391.8 364.3,220.7 	"/>
        </g>
        <g>
          <polygon fill="currentColor" points="87.7,49.7 411.8,250 364.3,279.3 87.7,108.2 	"/>
        </g>
        <polygon fill="currentColor" points="411.8,250 87.7,450.4 87.7,406.5 346.6,250 "/>
        <polygon fill="currentColor" points="87.7,49.7 411.7,250 340.3,249.8 87.7,91.7 "/>
      </g>
      <g id="icon-option">
        <circle fill="currentColor" cx="250" cy="85.9" r="55.1"/>
        <circle fill="currentColor" cx="250" cy="245.6" r="55.1"/>
        <circle fill="currentColor" cx="250" cy="405.2" r="55.1"/>
      </g>
      <g id="icon-upload">
        <path d="M11 14.9861C11 15.5384 11.4477 15.9861 12 15.9861C12.5523 15.9861 13 15.5384 13 14.9861V7.82831L16.2428 11.0711L17.657 9.65685L12.0001 4L6.34326 9.65685L7.75748 11.0711L11 7.82854V14.9861Z" fill="currentColor" />
        <path d="M4 14H6V18H18V14H20V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V14Z" fill="currentColor" />
      </g>
    </defs>
  </svg>
  <div class="modals">
    <div class="window form large" id="file-select" data-multiple="false" data-fileType="theme">
      <div class="wrapper row">
        <p class="label">Select uploaded file:</p>
        <label for="file-upload-input" class="button-like-main">Upload new</label>
        <input type="file" id="file-upload-input" class="display-none" multiple>
      </div>
      
      <div class="wrapper files"></div>
      
      <div class="wrapper sideways-end">
        <button class="cancel-modal">Cancel</button>
        <button class="submit" type="submit">Pick</button>
      </div>
  
      <div class="wrapper">
        <p class="blockquote error error-modal"></p>
      </div>
    </div>
  
    <div class="window form large" id="theme-creator" data-image-source="">
      <div class="wrapper">
        <p class="label">Theme name:</p>
        <input type="text" name="theme-name" id="theme-name" value="Theme">
      </div>
  
      <div class="wrapper">
        <p class="blockquote note">If the colors are not to your preference, try to adjust these sliders.</p>
      </div>
  
      <div class="wrapper">
        <p class="label">Parameter A:<br>(may change highlight color)</p>
        <input type="range" min="1" max="20" step="1" value="3" name="parameter-a" id="parameter-a">
        <p class="label">Parameter B:<br>(may change main color)</p>
        <input type="range" min="1" max="20" step="1" value="2" name="parameter-b" id="parameter-b">
        <p class="label">Color group size:<br>(lower the value, the more precise the result,<br>but the longer the wait time)</p>
        <input type="range" min="1" max="40" step="1" value="20" name="color-group-size" id="color-group-size">
      </div>
    
      <div class="wrapper sideways-end">
        <button class="cancel-modal">Cancel</button>
        <button class="submit" type="submit">Generate</button>
      </div>
    
      <div class="wrapper">
        <p class="blockquote error error-modal"></p>
      </div>
    </div>
  </div>
  <div class="display-none" id="page-data">