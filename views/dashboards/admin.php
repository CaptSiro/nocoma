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
  
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/main.js"></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/components/InfiniteScroller.js"></script>
  <script>
    AJAX.DOMAIN_HOME = "<?=$GLOBALS["__HOME__"]?>";
    AJAX.SERVER_HOME = "<?=$GLOBALS["__SERVER_HOME__"] ?? $GLOBALS["__HOME__"]?>";
    AJAX.HOST_NAME = "<?=$GLOBALS["env_home"]?>";
    AJAX.PROTOCOL = "<?=$GLOBALS["protocol"]?>";
  </script>
  
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/grid.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/dashboard.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/modal.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/forms.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/dashboard-admin.js" defer></script>
</head>
<body class="no-user-select">
  <div class="modals">
    <div class="window form large" id="take-down-window">
      <div class="wrapper">
        <p class="label">Take down <span id="post-title"></span></p>
      </div>
      
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
      <button class="logout" title="Logout" id="logout"><span style="font-family: monospace;">&lt;-</span></button>
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
          <section class="search-mount">
            <img src="<?=$GLOBALS["__HOME__"]?>/public/images/search-white.svg" alt="search">
            <input type="text">
          </section>
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
        
        <div class="scrollable appeals-view no-mg">
          
          <div class="appeal not-read">
            <div class="a-head">
              <div class="u-head default">
                <div class="start">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/stock/pfp.png" alt="pfp">
                  <div class="u-column">
                    <h4>Username</h4>
                    <span>website</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="a-body">
              <div class="related-post">
                <h3>Title of the post</h3>
              </div>
              
              <div class="a-msg">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit optio laudantium, perspiciatis ducimus expedita repellat quidem nobis temporibus deserunt alias quae ipsam quia quaerat a accusantium inventore tempora atque culpa?
              </div>
              
              <div class="a-controls">
                <button>View post</button>
                <button>Accept</button>
                <button>Decline</button>
              </div>
            </div>
          </div>
          
          <div class="appeal">
            <div class="a-head">
              <div class="u-head default">
                <div class="start">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/stock/pfp.png" alt="pfp">
                  <div class="u-column">
                    <h4>Username</h4>
                    <span>website</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="a-body">
              <div class="related-post">
                <h3>Title of the post</h3>
              </div>
              
              <div class="a-msg">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit optio laudantium, perspiciatis ducimus expedita repellat quidem nobis temporibus deserunt alias quae ipsam quia quaerat a accusantium inventore tempora atque culpa?
              </div>
              
              <div class="a-controls">
                <button>View post</button>
                <button>Accept</button>
                <button>Decline</button>
              </div>
            </div>
          </div>
          
          <div class="appeal">
            <div class="a-head">
              <div class="u-head default">
                <div class="start">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/stock/pfp.png" alt="pfp">
                  <div class="u-column">
                    <h4>Username</h4>
                    <span>website</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="a-body">
              <div class="related-post">
                <h3>Title of the post</h3>
              </div>
              
              <div class="a-msg">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit optio laudantium, perspiciatis ducimus expedita repellat quidem nobis temporibus deserunt alias quae ipsam quia quaerat a accusantium inventore tempora atque culpa?
              </div>
              
              <div class="a-controls">
                <button>View post</button>
                <button>Accept</button>
                <button>Decline</button>
              </div>
            </div>
          </div>
          
          <div class="appeal">
            <div class="a-head">
              <div class="u-head default">
                <div class="start">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/stock/pfp.png" alt="pfp">
                  <div class="u-column">
                    <h4>Username</h4>
                    <span>website</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="a-body">
              <div class="related-post">
                <h3>Title of the post</h3>
              </div>
              
              <div class="a-msg">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit optio laudantium, perspiciatis ducimus expedita repellat quidem nobis temporibus deserunt alias quae ipsam quia quaerat a accusantium inventore tempora atque culpa?
              </div>
              
              <div class="a-controls">
                <button>View post</button>
                <button>Accept</button>
                <button>Decline</button>
              </div>
            </div>
          </div>
          
          <div class="appeal">
            <div class="a-head">
              <div class="u-head default">
                <div class="start">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/stock/pfp.png" alt="pfp">
                  <div class="u-column">
                    <h4>Username</h4>
                    <span>website</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="a-body">
              <div class="related-post">
                <h3>Title of the post</h3>
              </div>
              
              <div class="a-msg">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit optio laudantium, perspiciatis ducimus expedita repellat quidem nobis temporibus deserunt alias quae ipsam quia quaerat a accusantium inventore tempora atque culpa?
              </div>
              
              <div class="a-controls">
                <button>View post</button>
                <button>Accept</button>
                <button>Decline</button>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  
  
    <div columns="7" class="c-grid link-element" auto-fill="true" id="profile">
      <section column="0-7" row="0-3" class="info">
        <div class="pfp">
          <img src="<?= $GLOBALS["__HOME__"] ?>/profile/picture/" alt="profile picture" id="profile-picture">
          <div class="edit">
            <label for="profile-picture-upload">
              <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/upload.svg" alt="upload" class="icon button-like">
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
    
      <!-- <section column="0-7" row="3-4">
        <div>settings</div>
      </section> -->
    </div>
  </main>
</body>
</html>