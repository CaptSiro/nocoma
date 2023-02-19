<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nocoma</title>
  
  <script>
    const HOME = "<?= $GLOBALS["__HOME__"] ?>";
  </script>

  <link rel="stylesheet" href="<?= $GLOBALS["__HOME__"] ?>/public/css/main.css">
  <link rel="stylesheet" href="<?= $GLOBALS["__HOME__"] ?>/public/css/nocoma.css">
  <link rel="icon" href="<?= $GLOBALS["__HOME__"] ?>/public/images/nocoma-icon.ico">

  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/components/Renderer3D.js"></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/main-v2.js"></script>
  <script>
    AJAX.DOMAIN_HOME = "<?=$GLOBALS["__HOME__"]?>";
    AJAX.SERVER_HOME = "<?=$GLOBALS["__SERVER_HOME__"] ?? $GLOBALS["__HOME__"]?>";
  </script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/theme.js"></script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/nocoma.js" defer></script>

  <link href='https://css.gg/chevron-double-down.css' rel='stylesheet'>
  <link href='https://css.gg/facebook.css' rel='stylesheet'>
  <link href='https://css.gg/youtube.css' rel='stylesheet'>
  <link href='https://css.gg/instagram.css' rel='stylesheet'>
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
    </defs>
  </svg>
  
  
  <header>
    <h3>Nocoma</h3>
    <div>
      <button class="login-button">Start creating</button>
    </div>
  </header>


  <section class="showcase snap-scrollable">
    <div class="animator-3d-container">
      <renderer-3d id="animator-3d"></renderer-3d>
    </div>

    <div class="abs">
      <h2>Create your blog fast.</h2>
      <p>Build your dream blog with our rich library of widgets.</p>
      <button class="start-now login-button">Start now</button>
    </div>

    <div class="more">
      <h3>Learn more</h3>
      <i class="gg-chevron-double-down text-icon"></i>
    </div>
  </section>

  <section class="bullet-points snap-scrollable">
    <div>
      <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/laptop.png" alt="">
    </div>
    <div class="text">
      <div class="container">
        <h3>Lorem ipsum dolor sit amet.</h3>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Placeat eaque sapiente odit possimus quaerat iste praesentium ad quia, corrupti, tempore maiores quis id vel soluta alias magni, nulla optio consequuntur?</p>
        <p>Labore animi perspiciatis ipsa sed quisquam odit, assumenda fugiat, eveniet harum maiores excepturi omnis facilis, quas distinctio repellat consequatur totam a voluptatem aspernatur at velit saepe nulla. Nihil, sequi accusamus!</p>
        <p>Possimus, nobis dicta ratione dolore reprehenderit totam qui itaque esse ut quisquam magnam minus, laudantium culpa odio quaerat! Quas cumque blanditiis ex soluta consectetur obcaecati. Saepe ut impedit quis modi!</p>
      </div>
    </div>
  </section>
  
  <section class="bullet-points snap-scrollable">
    <div class="text flipped">
      <div class="container">
        <h3>Wide library of themes</h3>
        <p>User selectable website themes are a great way to customize the look and feel of your website. With a wide range of themes to choose from, you can create a unique and personalized experience for your visitors.</p>
        <p>With themes that range from modern and minimal to vibrant and professional, you can find the perfect theme to match your brand and content.</p>
      </div>
    </div>
    <div id="themes">
      <svg viewBox="0 0 500 500" class="icon button-like-main left-arrow">
        <use xlink:href="#icon-arrow"></use>
      </svg>
      <div id="themes-showcase"></div>
      <svg viewBox="0 0 500 500" class="icon button-like-main right-arrow">
        <use xlink:href="#icon-arrow"></use>
      </svg>
    </div>
  </section>
  
  <section class="bullet-points snap-scrollable">
    <div>
      <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/laptop.png" alt="">
    </div>
    <div class="text">
      <div class="container">
        <h3>Lorem ipsum dolor sit amet.</h3>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Placeat eaque sapiente odit possimus quaerat iste praesentium ad quia, corrupti, tempore maiores quis id vel soluta alias magni, nulla optio consequuntur?</p>
        <p>Labore animi perspiciatis ipsa sed quisquam odit, assumenda fugiat, eveniet harum maiores excepturi omnis facilis, quas distinctio repellat consequatur totam a voluptatem aspernatur at velit saepe nulla. Nihil, sequi accusamus!</p>
        <p>Possimus, nobis dicta ratione dolore reprehenderit totam qui itaque esse ut quisquam magnam minus, laudantium culpa odio quaerat! Quas cumque blanditiis ex soluta consectetur obcaecati. Saepe ut impedit quis modi!</p>
      </div>
    </div>
  </section>
  
  <section class="bullet-points snap-scrollable">
    <div class="text flipped">
      <div class="container">
        <h3>Lorem ipsum dolor sit amet.</h3>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Placeat eaque sapiente odit possimus quaerat iste praesentium ad quia, corrupti, tempore maiores quis id vel soluta alias magni, nulla optio consequuntur?</p>
        <p>Labore animi perspiciatis ipsa sed quisquam odit, assumenda fugiat, eveniet harum maiores excepturi omnis facilis, quas distinctio repellat consequatur totam a voluptatem aspernatur at velit saepe nulla. Nihil, sequi accusamus!</p>
        <p>Possimus, nobis dicta ratione dolore reprehenderit totam qui itaque esse ut quisquam magnam minus, laudantium culpa odio quaerat! Quas cumque blanditiis ex soluta consectetur obcaecati. Saepe ut impedit quis modi!</p>
        <p>Possimus, nobis dicta ratione dolore reprehenderit totam qui itaque esse ut quisquam magnam minus, laudantium culpa odio quaerat! Quas cumque blanditiis ex soluta consectetur obcaecati. Saepe ut impedit quis modi!</p>
        <p>Possimus, nobis dicta ratione dolore reprehenderit totam qui itaque esse ut quisquam magnam minus, laudantium culpa odio quaerat! Quas cumque blanditiis ex soluta consectetur obcaecati. Saepe ut impedit quis modi!</p>
        <p>Possimus, nobis dicta ratione dolore reprehenderit totam qui itaque esse ut quisquam magnam minus, laudantium culpa odio quaerat! Quas cumque blanditiis ex soluta consectetur obcaecati. Saepe ut impedit quis modi!</p>
        <p>Possimus, nobis dicta ratione dolore reprehenderit totam qui itaque esse ut quisquam magnam minus, laudantium culpa odio quaerat! Quas cumque blanditiis ex soluta consectetur obcaecati. Saepe ut impedit quis modi!</p>
      </div>
    </div>
    <div>
      <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/website.png" alt="">
    </div>
  </section>

  <section class="start-now">
    <h3>Are you ready to leap forward?</h3>
    <button class="login-button">Start building now!</button>
  </section>

  <footer class="snap-scrollable">
    <div class="branding">
      <h3>Nocoma</h3>
      <div>
        <div class="padding">
          <i class="gg-facebook"></i>
        </div>
        <div class="padding">
          <i class="gg-instagram"></i>
        </div>
        <div class="padding">
          <i class="gg-youtube"></i>
        </div>
      </div>
    </div>
    <div class="columns">
      <div class="column">
        <h4>Section title</h4>
        <a class="lci">Lorem</a>
        <a class="lci">Lorem, ipsum.</a>
        <a class="lci">Lorem</a>
        <a class="lci">Lorem ipsum dolor sit.</a>
      </div>
      <div class="column">
        <h4>Section title</h4>
        <a class="lci">Lorem</a>
        <a class="lci">Lorem, ipsum.</a>
        <a class="lci">Lorem ipsum dolor sit.</a>
      </div>
      <div class="column">
        <h4>Section title</h4>
        <a class="lci">Lorem</a>
        <a class="lci">Lorem, ipsum.</a>
        <a class="lci">Lorem ipsum dolor sit.</a>
        <a class="lci">Lorem, ipsum.</a>
        <a class="lci">Lorem, ipsum.</a>
        <a class="lci">Lorem</a>
      </div>
      <div class="column">
        <h4>Section title</h4>
        <a class="lci">Lorem, ipsum.</a>
        <a class="lci">Lorem</a>
      </div>
    </div>
  </footer>
</body>
</html>