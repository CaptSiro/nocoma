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
  
  <link rel="icon" href="<?= $GLOBALS["__HOME__"] ?>/public/images/nocoma-icon.ico">
  
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
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/dashboard-user.js" defer></script>
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
      <g id="icon-private">
        <style type="text/css">
          .WiGafuJ8{display:none;}
          .Aap1myUl{display:inline;fill:none;stroke:#C2E2FF;stroke-width:5;stroke-miterlimit:10;}
          .kS1QU_v_{display:none;fill:none;stroke:#C2E2FF;stroke-width:5;stroke-miterlimit:10;}
        </style>
        <g id="Vrstva_1" class="WiGafuJ8">
          <rect fill="white" x="88.4" y="160.6" class="Aap1myUl" width="324.5" height="178.6"/>
    
          <rect fill="white" x="250" y="160.4" transform="matrix(-1 -1.224647e-16 1.224647e-16 -1 593.5474 499.5734)" class="Aap1myUl" width="93.5" height="178.7"/>
    
          <rect fill="white" x="156.5" y="160.4" transform="matrix(-1 -1.224647e-16 1.224647e-16 -1 406.4526 499.5734)" class="Aap1myUl" width="93.5" height="178.7"/>
          <rect fill="white" x="178.3" y="339.3" class="Aap1myUl" width="143.3" height="29.3"/>
          <rect fill="white" x="178.3" y="131" class="Aap1myUl" width="143.3" height="29.3"/>
        </g>
        <g id="Vrstva_2">
          <path fill="currentColor" class="kS1QU_v_" d="M111.5,249.8c92.8-88.2,185.5-88.2,278.3,0C297,338.1,204.3,338.1,111.5,249.8z"/>
          <g>
            <path fill="currentColor" d="M342.7,186.6l-9.9,9.9c22.3,12.5,43.3,30.3,61.8,53.4c-23.7,29.5-51.4,50.3-80.6,62.5c15.9-16.1,25.7-38.2,25.7-62.6
			c0-17.2-4.9-33.3-13.3-46.9l-9.9,9.9c6.2,10.9,9.7,23.6,9.7,37c0,41.5-33.5,75.2-75,75.6c-0.3,0-0.5,0-0.8,0
			c-13.4,0-26-3.6-36.9-9.7L198.3,331c17.1,5.3,34.6,7.9,52.1,7.9c0.1,0,0.2,0,0.3,0c1,0,2,0,3-0.1c57.3-1.2,114.6-30.8,159.3-89
			C391.9,222.4,368,201.3,342.7,186.6z"/>
            <path fill="currentColor" d="M136.2,364.5l41.3-41.3l10.2-10.2l0,0l9.6-9.6l26.7-26.7l53.5-53.5l26.7-26.7l19.6-19.6l41.3-41.3l0.5-0.5l-14.3-14.3
			l-48.2,48.2c-16.3-5.1-32.8-7.8-49.4-8.2c-1,0-2-0.1-3-0.1c-0.1,0-0.2,0-0.3,0c-58.6-0.1-117,29.6-162,89.2
			c20.9,27.6,44.8,48.8,70.1,63.6l-36.7,36.7L136.2,364.5z M250.5,174.2c0.3,0,0.5,0,0.8,0c13.3,0.1,25.8,3.7,36.6,9.8L259,212.9
			c-2.7-0.6-5.5-0.9-8.4-0.9c-20.9,0-37.8,16.9-37.8,37.8c0,2.9,0.3,5.7,0.9,8.4l-28.9,28.9c-6.3-11-9.8-23.7-9.8-37.3
			C175,208.1,208.8,174.3,250.5,174.2z M106.6,249.8c23.7-29.9,51.5-51,81.1-63.1c-16.2,16.1-26.2,38.4-26.2,63.1
			c0,17.3,4.9,33.5,13.5,47.2l-6.6,6.6C146.1,291,125.1,273.1,106.6,249.8z"/>
            <path fill="currentColor" d="M250.7,287.6c20.9,0,37.8-16.9,37.8-37.8c0-2.8-0.3-5.5-0.9-8.1l-45,45C245.2,287.3,247.9,287.6,250.7,287.6z"/>
          </g>
        </g>
      </g>
      <g id="icon-planned">
        <style type="text/css">
          .FoJD-zE1{display:none;}
          .TuY93SSb{display:inline;fill:none;stroke:#C2E2FF;stroke-width:5;stroke-miterlimit:10;}
          .Fk5NWUkh{display:none;fill:none;stroke:#C2E2FF;stroke-width:5;stroke-miterlimit:10;}
        </style>
        <g id="Vrstva_1" class="FoJD-zE1">
          <rect x="88.4" y="160.6" class="TuY93SSb" width="324.5" height="178.6"/>
    
          <rect x="250" y="160.4" transform="matrix(-1 -1.224647e-16 1.224647e-16 -1 593.5474 499.5734)" class="TuY93SSb" width="93.5" height="178.7"/>
    
          <rect x="156.5" y="160.4" transform="matrix(-1 -1.224647e-16 1.224647e-16 -1 406.4526 499.5734)" class="TuY93SSb" width="93.5" height="178.7"/>
          <rect x="178.3" y="339.3" class="TuY93SSb" width="143.3" height="29.3"/>
          <rect x="178.3" y="131" class="TuY93SSb" width="143.3" height="29.3"/>
        </g>
        <g id="Vrstva_2">
          <path class="Fk5NWUkh" d="M111.5,249.8c92.8-88.2,185.5-88.2,278.3,0C297,338.1,204.3,338.1,111.5,249.8z"/>
          <path fill="currentColor" d="M250.7,80.4C161,80.4,88.4,153,88.4,242.6S161,404.9,250.7,404.9s162.2-72.6,162.2-162.2S340.3,80.4,250.7,80.4z
		 M250.7,391.3c-82.1,0-148.7-66.6-148.7-148.7S168.5,93.9,250.7,93.9s148.7,66.6,148.7,148.7S332.8,391.3,250.7,391.3z"/>
          <path fill="currentColor" d="M338.6,235.9h-76.3c-1.2-2-2.9-3.7-4.9-4.9V127.6c0-3.7-3-6.8-6.8-6.8s-6.8,3-6.8,6.8v103.3c-4,2.3-6.8,6.7-6.8,11.7
		c0,7.5,6,13.5,13.5,13.5c5,0,9.3-2.7,11.7-6.8h76.3c3.7,0,6.8-3,6.8-6.8v0C345.4,238.9,342.3,235.9,338.6,235.9z"/>
        </g>
      </g>
      <g id="icon-public">
        <style type="text/css">
          .Qr1vcdQN{display:none;}
          .xirn0Ve_{display:inline;fill:none;stroke:#C2E2FF;stroke-width:5;stroke-miterlimit:10;}
          .Qrvbp8pV{display:none;fill:none;stroke:#C2E2FF;stroke-width:5;stroke-miterlimit:10;}
        </style>
        <g id="Vrstva_1" class="Qr1vcdQN">
          <rect x="88.4" y="160.6" class="xirn0Ve_" width="324.5" height="178.6"/>
    
          <rect x="250" y="160.4" transform="matrix(-1 -1.224647e-16 1.224647e-16 -1 593.5474 499.5734)" class="xirn0Ve_" width="93.5" height="178.7"/>
    
          <rect x="156.5" y="160.4" transform="matrix(-1 -1.224647e-16 1.224647e-16 -1 406.4526 499.5734)" class="xirn0Ve_" width="93.5" height="178.7"/>
          <rect x="178.3" y="339.3" class="xirn0Ve_" width="143.3" height="29.3"/>
          <rect x="178.3" y="131" class="xirn0Ve_" width="143.3" height="29.3"/>
        </g>
        <g id="Vrstva_2">
          <path class="Qrvbp8pV" d="M111.5,249.8c92.8-88.2,185.5-88.2,278.3,0C297,338.1,204.3,338.1,111.5,249.8z"/>
          <path fill="currentColor" d="M88.4,249.8c89.9,118.7,233.3,118.7,324.5,0C321.7,131,178.3,131,88.4,249.8z M106.6,249.8c79.8-100.9,207.1-100.9,288.1,0
		C313.7,350.6,186.4,350.6,106.6,249.8z"/>
          <path fill="currentColor" d="M250.7,160.7c-49.2,0-89.1,39.9-89.1,89.1s39.9,89.1,89.1,89.1c49.2,0,89.1-39.9,89.1-89.1S299.9,160.7,250.7,160.7z
		 M250.7,325.4c-41.8,0-75.6-33.8-75.6-75.6c0-41.8,33.8-75.6,75.6-75.6s75.6,33.8,75.6,75.6C326.3,291.6,292.4,325.4,250.7,325.4z"
          />
          <circle fill="currentColor" cx="250.6" cy="249.8" r="37.8"/>
        </g>
      </g>
      <g id="icon-pencil">
        <style type="text/css">
          .oag4rWlj{fill:none;stroke:currentColor;stroke-width:20;stroke-miterlimit:10;}
        </style>
        <g>
          <rect x="200.2" y="176" transform="matrix(0.7071 0.7071 -0.7071 0.7071 256.5491 -88.1385)" class="oag4rWlj" width="68.9" height="179.3"/>
          <polygon class="oag4rWlj" points="105.5,394.6 172,376.8 123.3,328.1 	"/>
          <rect x="311.4" y="119.6" transform="matrix(0.7071 0.7071 -0.7071 0.7071 210.2297 -199.4354)" class="oag4rWlj" width="68.9" height="68.9"/>
        </g>
      </g>
      <g id="icon-folder">
        <style type="text/css">
          .dE2H8xB3{fill:none;stroke:currentColor;stroke-width:20;stroke-miterlimit:10;}
          .Ov3qSjdv{fill:#020203;font-family: 'CreteRound-Italic', serif;font-size:12px;}
        </style>
        <g>
          <path class="dE2H8xB3" d="M53.9,169v195.7c0,0,2.4,32.1,32.2,32.1s327.8,0,327.8,0s32.2-1.5,32.2-32.2s0-195.6,0-195.6
		s2.4-32.2-38.4-32.2c0-14.1-14.2-14.1-14.2-14.1H277.1L250,103.4l-147.1-0.3c0,0-12.2,1.2-12,12s0,21.7,0,21.7h-4.8
		C86.1,136.9,53.9,139.7,53.9,169z"/>
          <line class="dE2H8xB3" x1="90.9" y1="136.9" x2="407.6" y2="136.9"/>
          <text transform="matrix(1 0 0 1 105.6339 124.8584)" class="Ov3qSjdv">SUS</text>
        </g>
      </g>
    </defs>
  </svg>
  
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
  
    <div class="window form large" id="appeal">
      <div class="wrapper">
        <p class="label">Appeal for take down on post: <span class="important-span" id="post-title"></span></p>
      </div>
    
      <div class="wrapper">
        <p class="label">Message: (Optional)</p>
        <textarea name="appeal-message" id="appeal-message" cols="30" rows="8"></textarea>
      </div>
    
      <div class="divider"></div>
    
      <div class="wrapper sideways-end">
        <button class="cancel-modal">Cancel</button>
        <button class="submit" type="submit">Submit</button>
      </div>
    
      <div class="wrapper">
        <p class="blockquote error error-modal"></p>
      </div>
    </div>
  
    <div class="window form large" id="take-down-message">
      <div class="wrapper">
        <p class="label">Take down for post <span class="important-span" id="post-title-message"></span>:</p>
      </div>
      
      <div class="wrapper">
        <p class="label white-space-normal" id="message-container"></p>
      </div>
    
      <div class="divider"></div>
    
      <div class="wrapper sideways-end">
        <button class="cancel-modal">Ok</button>
      </div>
    
      <div class="wrapper">
        <p class="blockquote error error-modal"></p>
      </div>
    </div>
  </div>
  
  <nav>
    <section>
      <button reference-to="posts" class="link-pointer active" title="Posts">
        <svg class="icon" viewBox="0 0 500 500" style="width: 100%;height: 100%;">
          <use xlink:href="#icon-pencil"></use>
        </svg>
      </button>
<!--      <button reference-to="comments" class="link-pointer" title="Comments"><span>2</span></button>-->
<!--      <button reference-to="themes" class="link-pointer" title="Themes"><span>3</span></button>-->
      <button reference-to="gallery" class="link-pointer" title="Gallery">
        <svg class="icon" viewBox="0 0 500 500" style="width: 100%;height: 100%;">
          <use xlink:href="#icon-folder"></use>
        </svg>
      </button>
      <button
        reference-to="profile"
        class="link-pointer"
        title="Profile"
        style="background-image: url(<?= $GLOBALS["__SERVER_HOME__"] ?>/profile/picture/?width=250&height=250&cropAndScale=true)"
        id="profile-picture-small"></button>
    </section>
    <section>
      <button class="logout" title="Logout" id="logout"><span style="font-family: monospace; font-weight: bolder;">&lt;-</span></button>
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
            <button class="change-post-type" data-type="0">All</button>
            <button class="change-post-type" data-type="1">Public</button>
            <button class="change-post-type" data-type="2">Planned</button>
            <button class="change-post-type" data-type="3">Private</button>
            <!-- <button>?Templates?</button> -->
          </section>
<!--          <section class="search-mount">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/search-white.svg" alt="search">-->
<!--            <input type="text">-->
<!--          </section>-->
        </header>
        
        <div class="scrollable full-span post-view no-mg"></div>
      </section>
      
      <!-- <section column="3-1" row="0-4">
        <check-box cb-border-radius="4px" cb-border="2px solid black" cb-background="#dde"></check-box>
      </section> -->
    </div>
    
    
<!--    <div columns="4" class="c-grid link-element" auto-fill="true" id="comments">-->
<!--      <section column="0-4" row="0-4">-->
<!--        <header class="no-mg">-->
<!--          <section class="controls">-->
<!--            <div class="separator right">Comments</div>-->
<!--            <button>Show not seen</button>-->
<!--          </section>-->
<!--          <section class="search-mount">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/search-white.svg" alt="search">-->
<!--            <input type="text">-->
<!--          </section>-->
<!--        </header>-->
<!--        -->
<!--        <div class="scrollable comment-view no-mg">-->
<!--          <div class="comment new">-->
<!--            <div class="c-head">-->
<!--              <h4 class="sender">Mr. Nobody</h4>-->
<!--            </div>-->
<!--            <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo labore error, temporibus molestias quod corrupti consectetur distinctio autem tempore. Ipsa ex non architecto nulla laudantium, dicta libero sit delectus autem.-->
<!--              Impedit doloribus nulla numquam explicabo rem aliquam, ad sed magnam molestiae veniam! Quidem, natus ad non quasi tempora, eos rem assumenda, placeat asperiores dolor soluta. Ab vitae expedita corrupti iste?-->
<!--              Provident expedita magnam, voluptas nostrum quidem suscipit recusandae tempora beatae sunt iusto architecto autem, vitae tempore sint et corporis necessitatibus veritatis qui illum officia hic facere. Nihil similique rerum quo!</p>-->
<!--            <div class="c-controls">-->
<!--              <button>Reply</button>-->
<!--              <button>Take down</button>-->
<!--            </div>-->
<!--            <div class="c-reply">-->
<!--              <h4 class="c-head">You:</h4>-->
<!--              <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quod asperiores, tempora molestias numquam dolor eligendi magni officiis explicabo? Quibusdam nisi praesentium tempore suscipit sunt itaque consequatur officia veritatis natus a.-->
<!--                Voluptatem, nobis. Amet quia dolore, a dolores aperiam molestias repellat magnam iste quos facilis! Tempora, enim neque eligendi porro, repudiandae illum eum earum error officiis nam totam cupiditate odio inventore.-->
<!--                Eveniet doloremque similique deserunt praesentium, fuga pariatur ad odit provident, veritatis nisi placeat vero itaque rerum in minima perspiciatis, quam tempora quibusdam eum. Molestias similique provident voluptatum id perferendis suscipit.-->
<!--                Sit nesciunt ab provident, quam eligendi, a rerum facere aliquid adipisci similique id repudiandae soluta sapiente placeat neque. Incidunt modi eos quia dolore molestiae sapiente officiis saepe iure adipisci odio.-->
<!--                Totam iste, ipsam laborum nihil dolor libero ut ipsum illum eum non consectetur qui aperiam quas. Commodi maiores necessitatibus sunt dicta non a molestias labore, cupiditate voluptate quos aspernatur illo.-->
<!--                Nisi, atque in facere vitae velit cum molestiae qui. Numquam temporibus dicta, magni fugiat nostrum iusto asperiores, sint aspernatur non quod in doloribus modi similique at ut reiciendis hic consequuntur?</p>-->
<!--              <div class="c-controls">-->
<!--                <button>Edit</button>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="comment new">-->
<!--            <div class="c-head">-->
<!--              <h4 class="sender">Mr. Nobody</h4>-->
<!--            </div>-->
<!--            <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo labore error, temporibus molestias quod corrupti consectetur distinctio autem tempore. Ipsa ex non architecto nulla laudantium, dicta libero sit delectus autem.-->
<!--              Impedit doloribus nulla numquam explicabo rem aliquam, ad sed magnam molestiae veniam! Quidem, natus ad non quasi tempora, eos rem assumenda, placeat asperiores dolor soluta. Ab vitae expedita corrupti iste?-->
<!--              Provident expedita magnam, voluptas nostrum quidem suscipit recusandae tempora beatae sunt iusto architecto autem, vitae tempore sint et corporis necessitatibus veritatis qui illum officia hic facere. Nihil similique rerum quo!</p>-->
<!--            <div class="c-controls">-->
<!--              <button>Reply</button>-->
<!--              <button>Take down</button>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="comment">-->
<!--            <div class="c-head">-->
<!--              <h4 class="sender">Mr. Nobody</h4>-->
<!--            </div>-->
<!--            <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo labore error, temporibus molestias quod corrupti consectetur distinctio autem tempore. Ipsa ex non architecto nulla laudantium, dicta libero sit delectus autem.-->
<!--              Impedit doloribus nulla numquam explicabo rem aliquam, ad sed magnam molestiae veniam! Quidem, natus ad non quasi tempora, eos rem assumenda, placeat asperiores dolor soluta. Ab vitae expedita corrupti iste?-->
<!--              Provident expedita magnam, voluptas nostrum quidem suscipit recusandae tempora beatae sunt iusto architecto autem, vitae tempore sint et corporis necessitatibus veritatis qui illum officia hic facere. Nihil similique rerum quo!</p>-->
<!--            <div class="c-controls">-->
<!--              <button>Reply</button>-->
<!--              <button>Take down</button>-->
<!--            </div>-->
<!--            <div class="c-reply">-->
<!--              <h4 class="c-head">You:</h4>-->
<!--              <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae, eaque nostrum error ea nemo cum et ipsa eligendi nam? In ullam, commodi quasi magnam aperiam minus eum rem sed aut.</p>-->
<!--              <div class="c-controls">-->
<!--                <button>Edit</button>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="comment">-->
<!--            <div class="c-head">-->
<!--              <h4 class="sender">Mr. Nobody</h4>-->
<!--            </div>-->
<!--            <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo labore error, temporibus molestias quod corrupti consectetur distinctio autem tempore. Ipsa ex non architecto nulla laudantium, dicta libero sit delectus autem.-->
<!--              Impedit doloribus nulla numquam explicabo rem aliquam, ad sed magnam molestiae veniam! Quidem, natus ad non quasi tempora, eos rem assumenda, placeat asperiores dolor soluta. Ab vitae expedita corrupti iste?-->
<!--              Provident expedita magnam, voluptas nostrum quidem suscipit recusandae tempora beatae sunt iusto architecto autem, vitae tempore sint et corporis necessitatibus veritatis qui illum officia hic facere. Nihil similique rerum quo!</p>-->
<!--            <div class="c-controls">-->
<!--              <button>Reply</button>-->
<!--              <button>Take down</button>-->
<!--            </div>-->
<!--            <div class="c-reply">-->
<!--              <h4 class="c-head">You:</h4>-->
<!--              <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae, eaque nostrum error ea nemo cum et ipsa eligendi nam? In ullam, commodi quasi magnam aperiam minus eum rem sed aut.</p>-->
<!--              <div class="c-controls">-->
<!--                <button>Edit</button>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="comment">-->
<!--            <div class="c-head">-->
<!--              <h4 class="sender">Mr. Nobody</h4>-->
<!--            </div>-->
<!--            <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo labore error, temporibus molestias quod corrupti consectetur distinctio autem tempore. Ipsa ex non architecto nulla laudantium, dicta libero sit delectus autem.-->
<!--              Impedit doloribus nulla numquam explicabo rem aliquam, ad sed magnam molestiae veniam! Quidem, natus ad non quasi tempora, eos rem assumenda, placeat asperiores dolor soluta. Ab vitae expedita corrupti iste?-->
<!--              Provident expedita magnam, voluptas nostrum quidem suscipit recusandae tempora beatae sunt iusto architecto autem, vitae tempore sint et corporis necessitatibus veritatis qui illum officia hic facere. Nihil similique rerum quo!</p>-->
<!--            <div class="c-controls">-->
<!--              <button>Reply</button>-->
<!--              <button>Take down</button>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="comment">-->
<!--            <div class="c-head">-->
<!--              <h4 class="sender">Mr. Nobody</h4>-->
<!--            </div>-->
<!--            <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo labore error, temporibus molestias quod corrupti consectetur distinctio autem tempore. Ipsa ex non architecto nulla laudantium, dicta libero sit delectus autem.-->
<!--              Impedit doloribus nulla numquam explicabo rem aliquam, ad sed magnam molestiae veniam! Quidem, natus ad non quasi tempora, eos rem assumenda, placeat asperiores dolor soluta. Ab vitae expedita corrupti iste?-->
<!--              Provident expedita magnam, voluptas nostrum quidem suscipit recusandae tempora beatae sunt iusto architecto autem, vitae tempore sint et corporis necessitatibus veritatis qui illum officia hic facere. Nihil similique rerum quo!</p>-->
<!--            <div class="c-controls">-->
<!--              <button>Reply</button>-->
<!--              <button>Take down</button>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="comment">-->
<!--            <div class="c-head">-->
<!--              <h4 class="sender">Mr. Nobody</h4>-->
<!--            </div>-->
<!--            <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo labore error, temporibus molestias quod corrupti consectetur distinctio autem tempore. Ipsa ex non architecto nulla laudantium, dicta libero sit delectus autem.-->
<!--              Impedit doloribus nulla numquam explicabo rem aliquam, ad sed magnam molestiae veniam! Quidem, natus ad non quasi tempora, eos rem assumenda, placeat asperiores dolor soluta. Ab vitae expedita corrupti iste?-->
<!--              Provident expedita magnam, voluptas nostrum quidem suscipit recusandae tempora beatae sunt iusto architecto autem, vitae tempore sint et corporis necessitatibus veritatis qui illum officia hic facere. Nihil similique rerum quo!</p>-->
<!--            <div class="c-controls">-->
<!--              <button>Reply</button>-->
<!--              <button>Take down</button>-->
<!--            </div>-->
<!--            <div class="c-reply">-->
<!--              <h4 class="c-head">You:</h4>-->
<!--              <p class="c-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae, eaque nostrum error ea nemo cum et ipsa eligendi nam? In ullam, commodi quasi magnam aperiam minus eum rem sed aut.</p>-->
<!--              <div class="c-controls">-->
<!--                <button>Edit</button>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="system-msg loading">-->
<!--            <p>Loading more comments...</p>-->
<!--          </div>-->
<!--          -->
<!--          <div class="system-msg empty">-->
<!--            <p>No comments?</p>-->
<!--          </div>-->
<!--        </div>-->
<!--      </section>-->
<!--    </div>-->
    
    
<!--    <div columns="4" class="c-grid link-element" auto-fill="true" id="themes">-->
<!--      <section column="0-4" row="0-4">-->
<!--        <header class="no-mg">-->
<!--          <section class="controls">-->
<!--            <div class="separator right">Themes</div>-->
<!--          </section>-->
<!--          <section class="search-mount">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/search-white.svg" alt="search">-->
<!--            <input type="text">-->
<!--          </section>-->
<!--        </header>-->
<!--        -->
<!--        <div class="scrollable theme-view no-mg">-->
<!--          <div class="theme selected">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/theme-stock-pictures/10732789753.png" alt="">-->
<!--            <div class="gradient"></div>-->
<!--            <div class="label">-->
<!--              <span>Theme 1</span>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="theme">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/theme-stock-pictures/246262618653.png" alt="">-->
<!--            <div class="gradient"></div>-->
<!--            <div class="label">-->
<!--              <span>Theme 2</span>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="theme">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/theme-stock-pictures/534527345717.png" alt="">-->
<!--            <div class="gradient"></div>-->
<!--            <div class="label">-->
<!--              <span>Theme 3</span>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="theme">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/theme-stock-pictures/697801256781.png" alt="">-->
<!--            <div class="gradient"></div>-->
<!--            <div class="label">-->
<!--              <span>Theme 4</span>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="theme">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/theme-stock-pictures/79649236.png" alt="">-->
<!--            <div class="gradient"></div>-->
<!--            <div class="label">-->
<!--              <span>Theme 5</span>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="theme">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/theme-stock-pictures/809734986523.png" alt="">-->
<!--            <div class="gradient"></div>-->
<!--            <div class="label">-->
<!--              <span>Theme 6</span>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="theme">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/theme-stock-pictures/9845092879345.png" alt="">-->
<!--            <div class="gradient"></div>-->
<!--            <div class="label">-->
<!--              <span>Theme 7</span>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="theme">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/theme-stock-pictures/98745154.png" alt="">-->
<!--            <div class="gradient"></div>-->
<!--            <div class="label">-->
<!--              <span>Theme 8</span>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="theme">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/theme-stock-pictures/10732789753.png" alt="">-->
<!--            <div class="gradient"></div>-->
<!--            <div class="label">-->
<!--              <span>Theme 1</span>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="theme">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/theme-stock-pictures/246262618653.png" alt="">-->
<!--            <div class="gradient"></div>-->
<!--            <div class="label">-->
<!--              <span>Theme 2</span>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="theme">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/theme-stock-pictures/534527345717.png" alt="">-->
<!--            <div class="gradient"></div>-->
<!--            <div class="label">-->
<!--              <span>Theme 3</span>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="theme">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/theme-stock-pictures/697801256781.png" alt="">-->
<!--            <div class="gradient"></div>-->
<!--            <div class="label">-->
<!--              <span>Theme 4</span>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="theme">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/theme-stock-pictures/79649236.png" alt="">-->
<!--            <div class="gradient"></div>-->
<!--            <div class="label">-->
<!--              <span>Theme 5</span>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="theme">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/theme-stock-pictures/809734986523.png" alt="">-->
<!--            <div class="gradient"></div>-->
<!--            <div class="label">-->
<!--              <span>Theme 6</span>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="theme">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/theme-stock-pictures/9845092879345.png" alt="">-->
<!--            <div class="gradient"></div>-->
<!--            <div class="label">-->
<!--              <span>Theme 7</span>-->
<!--            </div>-->
<!--          </div>-->
<!--          -->
<!--          <div class="theme">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/theme-stock-pictures/98745154.png" alt="">-->
<!--            <div class="gradient"></div>-->
<!--            <div class="label">-->
<!--              <span>Theme 8</span>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </section>-->
<!--    </div>-->
    
    
    <div columns="4" class="c-grid link-element" auto-fill="true" id="gallery">
      <section column="0-4" row="0-4">
        <header class="no-mg">
          <section class="controls no-user-select">
            <div class="separator right">Gallery</div>
            <button id="upload-files-button">Add</button>
            <div class="separator">Sort by:</div>
            <button class="change-file-order" data-order="0">Date added</button>
            <button class="change-file-order" data-order="1">Name</button>
            <button class="change-file-order" data-order="2">Size</button>
          </section>
<!--          <section class="search-mount">-->
<!--            <img src="--><?//= $GLOBALS["__HOME__"] ?><!--/public/images/search-white.svg" alt="search">-->
<!--            <input type="text">-->
<!--          </section>-->
        </header>
        
        <div class="scrollable files-view no-mg"></div>
      </section>
    </div>
    
    
    <div columns="7" class="c-grid link-element" auto-fill="true" id="profile">
      <section column="0-7" row="0-3" class="info">
        <div class="pfp">
          <img src="<?= $GLOBALS["__HOME__"] ?>/profile/picture/?width=250&height=250&cropAndScale=true" alt="profile picture" id="profile-picture">
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