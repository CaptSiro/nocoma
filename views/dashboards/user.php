<!DOCTYPE html>
<!-- TODO: FIX THIS -->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User - NoComa</title>
  
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/main.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/dashboard.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/user-dashboard.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/modal.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/forms.css">
  
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/main.js"></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/components/InfiniteScroller.js"></script>
  <script>
    AJAX.DOMAIN_HOME = "<?=$GLOBALS["__HOME__"]?>";
    AJAX.SERVER_HOME = "<?=$GLOBALS["SERVER_HOME"] ?? $GLOBALS["__HOME__"]?>";
  </script>
  
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/grid.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/dashboard.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/modal.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/forms.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/dashboard-user.js" defer></script>
</head>
<body class="no-user-select">
  <div class="modals">
    <div class="window form" id="create-post">
      <div class="wrapper">
        <p class="label">Post title:</p>
        <input type="text" name="title" id="n-title">
      </div>
  
      <div class="wrapper checkboxes">
        <label class="checkbox-container">
          <input type="checkbox" name="isPublic" id="n-is-public">
          <span>Set as public</span>
        </label>
        <label class="checkbox-container">
          <input type="checkbox" name="isHomePage" id="n-is-home-page">
          <span>Set as home page</span>
        </label>
        <label class="checkbox-container">
          <input checked type="checkbox" name="areCommentsAvailable" id="n-are-comments-available">
          <span>Enable comments</span>
        </label>
      </div>
  
      <div class="divider"></div>
      
      <div class="wrapper sideways-end">
        <button class="cancel-modal">Cancel</button>
        <button class="submit" type="submit">Create</button>
      </div>
  
      <div class="wrapper">
        <p class="blockquote error error-modal"></p>
      </div>
    </div>
  
    <div class="window form large" id="upload-files">
      <div class="drop-area">
        <div class="hint">
          <span>Drag and drop your files here.</span>
          <label for="upload-files-input" class="button-like-main">
            Or select files
          </label>
          <input type="file" name="uploaded[]" id="upload-files-input" multiple>
        </div>
      </div>
      
      <div class="wrapper">
        <p class="label">Selected files:</p>
        <div class="selected-files"></div>
      </div>
    
      <div class="wrapper sideways-end">
        <button class="cancel-modal">Cancel</button>
        <button class="submit" type="submit">Upload</button>
      </div>
    
      <div class="wrapper">
        <p class="blockquote error error-modal"></p>
      </div>
    </div>
  </div>
  
  <nav>
    <section>
      <button reference-to="posts" class="link-pointer active" title="Posts"><span>1</span></button>
      <button reference-to="comments" class="link-pointer" title="Comments"><span>2</span></button>
      <button reference-to="themes" class="link-pointer" title="Themes"><span>3</span></button>
      <button reference-to="gallery" class="link-pointer" title="Gallery"><span>4</span></button>
      <button
        reference-to="profile"
        class="link-pointer"
        title="Profile"
        style="background-image: url(<?= $GLOBALS["__SERVER_HOME__"] ?>/profile/picture)" id="profile-picture-small"></button>
    </section>
    <section>
      <button class="logout" title="Logout" id="logout"><span style="font-family: monospace;">&lt;-</span></button>
    </section>
  </nav>
  
  <main>
    <div columns="4" class="c-grid link-element" auto-fill="true" id="posts">
      <section column="0-4" row="0-4">
        <header class="no-mg">
          <section class="controls">
            <div class="separator right">Posts</div>
            <button id="create-post-button">New</button>
            <div class="separator">Show:</div>
            <button>Public</button>
            <button>Planned</button>
            <button>Private</button>
            <!-- <button>?Templates?</button> -->
          </section>
          <section class="search-mount">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/search-white.svg" alt="search">
            <input type="text">
          </section>
        </header>
        
        <div class="scrollable full-span post-view no-mg"></div>
      </section>
      
      <!-- <section column="3-1" row="0-4">
        <check-box cb-border-radius="4px" cb-border="2px solid black" cb-background="#dde"></check-box>
      </section> -->
    </div>
    
    
    <div columns="4" class="c-grid link-element" auto-fill="true" id="comments">
      <section column="0-4" row="0-4">
        <header class="no-mg">
          <section class="controls">
            <div class="separator right">Comments</div>
            <button>Show not seen</button>
          </section>
          <section class="search-mount">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/search-white.svg" alt="search">
            <input type="text">
          </section>
        </header>
        
        <div class="scrollable comment-view no-mg">
          <div class="comment new">
            <div class="c-head">
              <h4 class="sender">Mr. Nobody</h4>
            </div>
            <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo labore error, temporibus molestias quod corrupti consectetur distinctio autem tempore. Ipsa ex non architecto nulla laudantium, dicta libero sit delectus autem.
              Impedit doloribus nulla numquam explicabo rem aliquam, ad sed magnam molestiae veniam! Quidem, natus ad non quasi tempora, eos rem assumenda, placeat asperiores dolor soluta. Ab vitae expedita corrupti iste?
              Provident expedita magnam, voluptas nostrum quidem suscipit recusandae tempora beatae sunt iusto architecto autem, vitae tempore sint et corporis necessitatibus veritatis qui illum officia hic facere. Nihil similique rerum quo!</p>
            <div class="c-controls">
              <button>Reply</button>
              <button>Take down</button>
            </div>
            <div class="c-reply">
              <h4 class="c-head">You:</h4>
              <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quod asperiores, tempora molestias numquam dolor eligendi magni officiis explicabo? Quibusdam nisi praesentium tempore suscipit sunt itaque consequatur officia veritatis natus a.
                Voluptatem, nobis. Amet quia dolore, a dolores aperiam molestias repellat magnam iste quos facilis! Tempora, enim neque eligendi porro, repudiandae illum eum earum error officiis nam totam cupiditate odio inventore.
                Eveniet doloremque similique deserunt praesentium, fuga pariatur ad odit provident, veritatis nisi placeat vero itaque rerum in minima perspiciatis, quam tempora quibusdam eum. Molestias similique provident voluptatum id perferendis suscipit.
                Sit nesciunt ab provident, quam eligendi, a rerum facere aliquid adipisci similique id repudiandae soluta sapiente placeat neque. Incidunt modi eos quia dolore molestiae sapiente officiis saepe iure adipisci odio.
                Totam iste, ipsam laborum nihil dolor libero ut ipsum illum eum non consectetur qui aperiam quas. Commodi maiores necessitatibus sunt dicta non a molestias labore, cupiditate voluptate quos aspernatur illo.
                Nisi, atque in facere vitae velit cum molestiae qui. Numquam temporibus dicta, magni fugiat nostrum iusto asperiores, sint aspernatur non quod in doloribus modi similique at ut reiciendis hic consequuntur?</p>
              <div class="c-controls">
                <button>Edit</button>
              </div>
            </div>
          </div>
          
          <div class="comment new">
            <div class="c-head">
              <h4 class="sender">Mr. Nobody</h4>
            </div>
            <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo labore error, temporibus molestias quod corrupti consectetur distinctio autem tempore. Ipsa ex non architecto nulla laudantium, dicta libero sit delectus autem.
              Impedit doloribus nulla numquam explicabo rem aliquam, ad sed magnam molestiae veniam! Quidem, natus ad non quasi tempora, eos rem assumenda, placeat asperiores dolor soluta. Ab vitae expedita corrupti iste?
              Provident expedita magnam, voluptas nostrum quidem suscipit recusandae tempora beatae sunt iusto architecto autem, vitae tempore sint et corporis necessitatibus veritatis qui illum officia hic facere. Nihil similique rerum quo!</p>
            <div class="c-controls">
              <button>Reply</button>
              <button>Take down</button>
            </div>
          </div>
          
          <div class="comment">
            <div class="c-head">
              <h4 class="sender">Mr. Nobody</h4>
            </div>
            <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo labore error, temporibus molestias quod corrupti consectetur distinctio autem tempore. Ipsa ex non architecto nulla laudantium, dicta libero sit delectus autem.
              Impedit doloribus nulla numquam explicabo rem aliquam, ad sed magnam molestiae veniam! Quidem, natus ad non quasi tempora, eos rem assumenda, placeat asperiores dolor soluta. Ab vitae expedita corrupti iste?
              Provident expedita magnam, voluptas nostrum quidem suscipit recusandae tempora beatae sunt iusto architecto autem, vitae tempore sint et corporis necessitatibus veritatis qui illum officia hic facere. Nihil similique rerum quo!</p>
            <div class="c-controls">
              <button>Reply</button>
              <button>Take down</button>
            </div>
            <div class="c-reply">
              <h4 class="c-head">You:</h4>
              <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae, eaque nostrum error ea nemo cum et ipsa eligendi nam? In ullam, commodi quasi magnam aperiam minus eum rem sed aut.</p>
              <div class="c-controls">
                <button>Edit</button>
              </div>
            </div>
          </div>
          
          <div class="comment">
            <div class="c-head">
              <h4 class="sender">Mr. Nobody</h4>
            </div>
            <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo labore error, temporibus molestias quod corrupti consectetur distinctio autem tempore. Ipsa ex non architecto nulla laudantium, dicta libero sit delectus autem.
              Impedit doloribus nulla numquam explicabo rem aliquam, ad sed magnam molestiae veniam! Quidem, natus ad non quasi tempora, eos rem assumenda, placeat asperiores dolor soluta. Ab vitae expedita corrupti iste?
              Provident expedita magnam, voluptas nostrum quidem suscipit recusandae tempora beatae sunt iusto architecto autem, vitae tempore sint et corporis necessitatibus veritatis qui illum officia hic facere. Nihil similique rerum quo!</p>
            <div class="c-controls">
              <button>Reply</button>
              <button>Take down</button>
            </div>
            <div class="c-reply">
              <h4 class="c-head">You:</h4>
              <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae, eaque nostrum error ea nemo cum et ipsa eligendi nam? In ullam, commodi quasi magnam aperiam minus eum rem sed aut.</p>
              <div class="c-controls">
                <button>Edit</button>
              </div>
            </div>
          </div>
          
          <div class="comment">
            <div class="c-head">
              <h4 class="sender">Mr. Nobody</h4>
            </div>
            <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo labore error, temporibus molestias quod corrupti consectetur distinctio autem tempore. Ipsa ex non architecto nulla laudantium, dicta libero sit delectus autem.
              Impedit doloribus nulla numquam explicabo rem aliquam, ad sed magnam molestiae veniam! Quidem, natus ad non quasi tempora, eos rem assumenda, placeat asperiores dolor soluta. Ab vitae expedita corrupti iste?
              Provident expedita magnam, voluptas nostrum quidem suscipit recusandae tempora beatae sunt iusto architecto autem, vitae tempore sint et corporis necessitatibus veritatis qui illum officia hic facere. Nihil similique rerum quo!</p>
            <div class="c-controls">
              <button>Reply</button>
              <button>Take down</button>
            </div>
          </div>
          
          <div class="comment">
            <div class="c-head">
              <h4 class="sender">Mr. Nobody</h4>
            </div>
            <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo labore error, temporibus molestias quod corrupti consectetur distinctio autem tempore. Ipsa ex non architecto nulla laudantium, dicta libero sit delectus autem.
              Impedit doloribus nulla numquam explicabo rem aliquam, ad sed magnam molestiae veniam! Quidem, natus ad non quasi tempora, eos rem assumenda, placeat asperiores dolor soluta. Ab vitae expedita corrupti iste?
              Provident expedita magnam, voluptas nostrum quidem suscipit recusandae tempora beatae sunt iusto architecto autem, vitae tempore sint et corporis necessitatibus veritatis qui illum officia hic facere. Nihil similique rerum quo!</p>
            <div class="c-controls">
              <button>Reply</button>
              <button>Take down</button>
            </div>
          </div>
          
          <div class="comment">
            <div class="c-head">
              <h4 class="sender">Mr. Nobody</h4>
            </div>
            <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo labore error, temporibus molestias quod corrupti consectetur distinctio autem tempore. Ipsa ex non architecto nulla laudantium, dicta libero sit delectus autem.
              Impedit doloribus nulla numquam explicabo rem aliquam, ad sed magnam molestiae veniam! Quidem, natus ad non quasi tempora, eos rem assumenda, placeat asperiores dolor soluta. Ab vitae expedita corrupti iste?
              Provident expedita magnam, voluptas nostrum quidem suscipit recusandae tempora beatae sunt iusto architecto autem, vitae tempore sint et corporis necessitatibus veritatis qui illum officia hic facere. Nihil similique rerum quo!</p>
            <div class="c-controls">
              <button>Reply</button>
              <button>Take down</button>
            </div>
            <div class="c-reply">
              <h4 class="c-head">You:</h4>
              <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae, eaque nostrum error ea nemo cum et ipsa eligendi nam? In ullam, commodi quasi magnam aperiam minus eum rem sed aut.</p>
              <div class="c-controls">
                <button>Edit</button>
              </div>
            </div>
          </div>
          
          <div class="system-msg loading">
            <p>Loading more comments...</p>
          </div>
          
          <div class="system-msg empty">
            <p>No comments?</p>
          </div>
        </div>
      </section>
    </div>
    
    
    <div columns="4" class="c-grid link-element" auto-fill="true" id="themes">
      <section column="0-4" row="0-4">
        <header class="no-mg">
          <section class="controls">
            <div class="separator right">Themes</div>
          </section>
          <section class="search-mount">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/search-white.svg" alt="search">
            <input type="text">
          </section>
        </header>
        
        <div class="scrollable theme-view no-mg">
          <div class="theme selected">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/10732789753.png" alt="">
            <div class="gradient"></div>
            <div class="label">
              <span>Theme 1</span>
            </div>
          </div>
          
          <div class="theme">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/246262618653.png" alt="">
            <div class="gradient"></div>
            <div class="label">
              <span>Theme 2</span>
            </div>
          </div>
          
          <div class="theme">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/534527345717.png" alt="">
            <div class="gradient"></div>
            <div class="label">
              <span>Theme 3</span>
            </div>
          </div>
          
          <div class="theme">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/697801256781.png" alt="">
            <div class="gradient"></div>
            <div class="label">
              <span>Theme 4</span>
            </div>
          </div>
          
          <div class="theme">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/79649236.png" alt="">
            <div class="gradient"></div>
            <div class="label">
              <span>Theme 5</span>
            </div>
          </div>
          
          <div class="theme">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/809734986523.png" alt="">
            <div class="gradient"></div>
            <div class="label">
              <span>Theme 6</span>
            </div>
          </div>
          
          <div class="theme">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/9845092879345.png" alt="">
            <div class="gradient"></div>
            <div class="label">
              <span>Theme 7</span>
            </div>
          </div>
          
          <div class="theme">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/98745154.png" alt="">
            <div class="gradient"></div>
            <div class="label">
              <span>Theme 8</span>
            </div>
          </div>
          
          <div class="theme">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/10732789753.png" alt="">
            <div class="gradient"></div>
            <div class="label">
              <span>Theme 1</span>
            </div>
          </div>
          
          <div class="theme">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/246262618653.png" alt="">
            <div class="gradient"></div>
            <div class="label">
              <span>Theme 2</span>
            </div>
          </div>
          
          <div class="theme">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/534527345717.png" alt="">
            <div class="gradient"></div>
            <div class="label">
              <span>Theme 3</span>
            </div>
          </div>
          
          <div class="theme">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/697801256781.png" alt="">
            <div class="gradient"></div>
            <div class="label">
              <span>Theme 4</span>
            </div>
          </div>
          
          <div class="theme">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/79649236.png" alt="">
            <div class="gradient"></div>
            <div class="label">
              <span>Theme 5</span>
            </div>
          </div>
          
          <div class="theme">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/809734986523.png" alt="">
            <div class="gradient"></div>
            <div class="label">
              <span>Theme 6</span>
            </div>
          </div>
          
          <div class="theme">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/9845092879345.png" alt="">
            <div class="gradient"></div>
            <div class="label">
              <span>Theme 7</span>
            </div>
          </div>
          
          <div class="theme">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/98745154.png" alt="">
            <div class="gradient"></div>
            <div class="label">
              <span>Theme 8</span>
            </div>
          </div>
        </div>
      </section>
    </div>
    
    
    <div columns="4" class="c-grid link-element" auto-fill="true" id="gallery">
      <section column="0-4" row="0-4">
        <header class="no-mg">
          <section class="controls no-user-select">
            <div class="separator right">Gallery</div>
            <button id="upload-files-button">Add</button>
            <div class="dropdown">
              <button>Sort by</button>
              <div class="items">
                <div class="change-order" data-order="0">Date added</div>
                <div class="change-order" data-order="1">Name</div>
                <div class="change-order" data-order="2">Size</div>
              </div>
            </div>
          </section>
          <section class="search-mount">
            <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/search-white.svg" alt="search">
            <input type="text">
          </section>
        </header>
        
<!--        <div class="f-v-control no-mg">-->
<!--          <check-box cb-border-radius="4px" cb-border="2px solid var(--container-3)" cb-background="var(--container-0)" cb-background-checked="var(--container-opposite-0)">Select all</check-box>-->
<!--          <button class="inverse">Remove selected</button>-->
<!--        </div>-->
        
        <div class="scrollable files-view no-mg"></div>
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
            <span class="website"><?= $GLOBALS["user"]->website ?>.<?= $GLOBALS["env_home"] ?></span>
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