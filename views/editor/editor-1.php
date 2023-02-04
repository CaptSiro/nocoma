
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
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/user-theme-setter.js" url="/theme/website/<?= $GLOBALS["webpage"]->src ?>"></script>
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
  <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: none;">
    <defs>
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
      <g id="WLink">
        <path fill="currentColor" d="M250.6,87.6c-89.6,0-162.3,72.6-162.3,162.3S161,412.1,250.6,412.1s162.3-72.6,162.3-162.3S340.3,87.6,250.6,87.6z
	 M250.6,398.5c-82,0-148.5-66.5-148.5-148.5s66.5-148.5,148.5-148.5S399.1,168,399.1,250S332.6,398.5,250.6,398.5z"/>
        <rect fill="currentColor" x="242.7" y="93.3" width="13.6" height="313"/>
        <path fill="currentColor" d="M251.3,401.3l-15.1,10.1C141,377.8,108.3,163.5,235,88.3l16.3,10C157.8,141.8,128.3,339.8,251.3,401.3z"/>
        <path fill="currentColor" d="M251.3,99.6l15-11.3c95.1,33.7,125.7,247.9-1.1,323.1l-13.9-8.8C344.8,359.1,374.3,161.1,251.3,99.6z"/>
        <path fill="currentColor" d="M145.8,134.2c27.5,24.4,168.8,29.5,208.6,0c6.6,5.8,5.6,4.3,12.2,10.1c-30.5,31.6-198.4,31-228.9-0.5
	C141.6,138,145.8,134.2,145.8,134.2z"/>
        <path fill="currentColor" d="M355.5,365.2c-27.3-24.4-167.6-29.5-207.2,0c-14.3-12.1-7.7-6.2-14.3-12.1c30.3-31.6,210.6-29.5,232.4,0.9
	C355.5,365.2,355.5,365.2,355.5,365.2z"/>
        <rect fill="currentColor" x="95.2" y="242.8" width="308.7" height="14.1"/>
      </g>
      <g id="WImage">
        <path fill="currentColor" d="M87,87v326h326V87H87z M389.5,389.5h-278v-278h278V389.5z"/>
        <polygon fill="currentColor" points="217.6,224.6 136,366 299.3,366 "/>
        <polygon fill="currentColor" points="305.5,263 246.1,366 365,366 "/>
        <circle fill="currentColor" cx="305.5" cy="187.8" r="26.7"/>
      </g>
      <g id="WText">
        <rect fill="currentColor" x="87" y="385.4" width="253.5" height="27.6"/>
        <rect fill="currentColor" x="87.5" y="160.9" width="326" height="27.6"/>
        <rect fill="currentColor" x="87" y="234.8" width="326" height="27.6"/>
        <rect fill="currentColor" x="87.5" y="310.1" width="326" height="27.6"/>
        <rect fill="currentColor" x="155.2" y="87" width="258.3" height="27.6"/>
      </g>
      <g id="WHeading">
        <path fill="currentColor" d="M284.7,147.1c-23.4,2.9-25.7,4.6-25.7,34.7V325c0,30,2.5,31.1,25.7,34.3v10H209v-10c23.4-3.6,25.7-4.3,25.7-34.3v-71.5
			h-96.5V325c0,29.7,2.3,30.7,25.4,34.3v10H88.2v-10c22.9-3.2,25.4-4.3,25.4-34.3V181.7c0-30-2.5-31.8-25.4-34.7v-10h75.4v10
			c-22.6,2.5-25.4,5-25.4,34.7v56.8h96.5v-56.8c0-29.7-3.1-32.2-25.7-34.7v-10h75.6V147.1z"/>
        <path fill="currentColor" d="M322.8,369.3v-10c31.3-2.5,33-4.3,33-31.1V191c0-18.2-2-18.9-14.4-20.4l-13.8-1.8v-8.9c18.6-3.9,37.3-9.6,51.7-17.5v185.8
			c0,27.2,1.7,28.6,33.3,31.1v10H322.8z"/>
      </g>
      <g id="WCode">
        <style type="text/css">
          .st0{font-family: 'SegoeUI-Semibold', monospace;}
          .st1{font-size:210px;}
        </style>
        <text transform="matrix(1 0 0 1 61.0796 314.2594)" class="st0 st1" fill="currentColor">&lt;/&gt;</text>
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
  </div>
  <div class="display-none" id="page-data">