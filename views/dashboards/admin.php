<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - NoComa</title>
  
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/main.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/dashboard.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/admin-dashboard.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/modal.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/forms.css">
  
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/main-v2.js"></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/custom.js"></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/components/InfiniteScroller.js"></script>
  <script>
    AJAX.DOMAIN_HOME = "<?=$GLOBALS["__HOME__"]?>";
    AJAX.SERVER_HOME = "<?=$GLOBALS["__SERVER_HOME__"] ?? $GLOBALS["__HOME__"]?>";
    AJAX.HOST_NAME = "<?=$GLOBALS["env_home"]?>";
    AJAX.PROTOCOL = "<?=$GLOBALS["protocol"]?>";
  </script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/theme.js"></script>
  
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/grid.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/dashboard.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/modal.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/forms.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/dashboard-admin.js" defer></script>
</head>
<body class="no-user-select">
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
    </defs>
  </svg>
  
  <div class="modals">
    <div class="window form large" id="take-down-window">
      <div class="wrapper">
        <p class="label">Take down <span id="post-title"></span></p>
      </div>
      
      <div class="wrapper"></div>
      
      <div class="wrapper">
        <p class="label">Message:</p>
        <textarea name="take-down-message" id="take-down-message" cols="30" rows="8"></textarea>
      </div>

      <div class="divider"></div>
    
      <div class="wrapper sideways-end">
        <button class="cancel-modal">Cancel</button>
        <button class="submit" type="submit">Take down</button>
      </div>
    
      <div class="wrapper">
        <p class="blockquote error error-modal"></p>
      </div>
    </div>
  </div>
  
  <nav>
    <section>
      <button reference-to="users" class="link-pointer active" title="Users"><span>1</span></button>
      <button reference-to="appeals" class="link-pointer" title="Appeals"><span>2</span></button>
      <button reference-to="profile" class="link-pointer" title="Profile" style="background-image: url(<?= $GLOBALS["__SERVER_HOME__"] ?>/profile/picture)" id="profile-picture-small"></button>
    </section>
    <section>
      <button class="logout" title="Logout" id="logout"><span style="font-family: monospace; font-weight: bolder; ">&lt;-</span></button>
    </section>
  </nav>
  
  <main>
    <div columns="4" class="c-grid link-element" auto-fill="true" id="users">
      <section column="0-4" row="0-4">
        <header class="no-mg">
          <section class="controls">
            <div class="separator right">Users</div>
            <button class="change-type" data-type="0">All</button>
            <button class="change-type" data-type="1">Banned</button>
          </section>
<!--          <section class="search-mount">-->
<!--            <img src="--><?//=$GLOBALS["__HOME__"]?><!--/public/images/search-white.svg" alt="search">-->
<!--            <input type="text">-->
<!--          </section>-->
        </header>
        
        <div class="scrollable user-view no-mg"></div>
      </section>
    </div>
    
    <div columns="4" class="c-grid link-element" auto-fill="true" id="appeals">
      <section column="0-4" row="0-4">
        <header class="no-mg">
          <section class="controls">
            <div class="separator right">Appeals</div>
            <button class="change-appeal-type" data-type="0">All</button>
            <button class="change-appeal-type" data-type="1">Not read</button>
          </section>
        </header>
        
        <div class="scrollable appeals-view no-mg"></div>
      </section>
    </div>
  
  
    <div columns="7" class="c-grid link-element" auto-fill="true" id="profile">
      <section column="0-7" row="0-3" class="info">
        <div class="pfp">
          <img src="<?= $GLOBALS["__HOME__"] ?>/profile/picture/" alt="profile picture" id="profile-picture">
          <div class="edit">
            <label for="profile-picture-upload">
              <svg class="icon button-like" viewBox="0 0 24 24" style="width: 100%;height: 100%;">
                <use xlink:href="#icon-upload"></use>
              </svg>
<!--              <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/upload.svg" alt="upload" class="icon button-like">-->
            </label>
            <input type="file" name="profile-picture" id="profile-picture-upload" class="display-none" accept="image/png, image/gif, image/jpeg">
          </div>
        </div>
        <div class="i-column">
          <div class="field username">
            <h3 class="selectable"><?= $GLOBALS["user"]->username ?></h3>
            <button>Edit</button>
          </div>
          <div class="field">
            <span><?= $GLOBALS["user"]->email ?></span>
          </div>
          <div class="field end">
            <button id="reset-password">Reset password</button>
          </div>
        </div>
      </section>
  
      <section column="0-7" row="3-4" class="settings">
        <div class="s-column">
          <h3>Select theme:</h3>
          <div class="select-dropdown" id="theme-select">
            <div class="label">
              <span id="theme-name">Loading theme...</span>
              <svg class="icon" viewBox="0 0 500 500">
                <use xlink:href="#icon-arrow"></use>
              </svg>
            </div>
      
            <div class="content"></div>
          </div>
        </div>
      </section>
    </div>
  </main>
</body>
</html>