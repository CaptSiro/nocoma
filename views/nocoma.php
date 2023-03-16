<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="The user creates a domain under which he/she then manages the articles of his/her blog. Each article is treated as a web page that will be editable. It is possible to choose a theme for the entire domain.">
  <meta name="keywords" content="domain, blog, articles, CMS, personification, themes, design">
  
  <title>Nocoma</title>
  
  <script>
    const HOME = "<?= $GLOBALS["__HOME__"] ?>";
  </script>

  <link rel="stylesheet" href="<?= $GLOBALS["__HOME__"] ?>/public/css/main.css">
  <link rel="stylesheet" href="<?= $GLOBALS["__HOME__"] ?>/public/css/nocoma.css">
  <link rel="icon" href="<?= $GLOBALS["__HOME__"] ?>/public/images/nocoma-icon.ico">

  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/components/Renderer3D.js"></script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/main-v2.js"></script>
  <script>
    AJAX.DOMAIN_HOME = "<?=$GLOBALS["__HOME__"]?>";
    AJAX.SERVER_HOME = "<?=$GLOBALS["__SERVER_HOME__"] ?? $GLOBALS["__HOME__"]?>";
  </script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/theme.js"></script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/nocoma.js" defer></script>

<!--  <link href='https://css.gg/chevron-double-down.css' rel='stylesheet'>-->
<!--  <link href='https://css.gg/facebook.css' rel='stylesheet'>-->
<!--  <link href='https://css.gg/youtube.css' rel='stylesheet'>-->
<!--  <link href='https://css.gg/instagram.css' rel='stylesheet'>-->
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
      <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/laptop.png?width=600" alt="">
    </div>
    <div class="text">
      <div class="container">
        <h3>Why website building app?</h3>
        <p>Create a website you'll love with our website building app!</p>
        <p>Our user-friendly app offers a range of features that make it easy to design, customize, and launch your website in no time.</p>
        <p>With our drag-and-drop interface, dynamic themes, and mobile-responsive designs, you can create a stunning website that looks great on any device. Best of all, our app offers free hosting, so you can get your website online without breaking the bank.</p>
        <p>Start building your dream website today with our all-in-one website building app!</p>
      </div>
    </div>
  </section>
  
  <section class="bullet-points snap-scrollable">
    <div class="text flipped">
      <div class="container">
        <h3>Wide library of themes</h3>
        <p>Transform your website with dynamic image themes!</p>
        <p>Our app offers a unique feature that turns your favorite images into stunning website themes. Simply upload your image, and our app will automatically create a dynamic theme that perfectly complements your content. With our image themes, you can add a personalized touch to your website and captivate your audience.</p>
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
      <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/stock/editor.png?width=600" alt="">
    </div>
    <div class="text">
      <div class="container">
        <h3>Easy to edit</h3>
        <p>Creating a website has never been easier with our website building app!</p>
        <p>Our user-friendly drag-and-drop interface allows you to create a professional-looking website in no time, without any coding knowledge. With just a few clicks, you can choose from a wide range of widgets, including text boxes, images, and videos.</p>
        <p>Our app is perfect for beginners and experienced website builders alike, offering a seamless website building experience that will save you time and hassle. Don't wait any longer, start building your dream website today with our easy-to-use website building app!</p>
      </div>
    </div>
  </section>
  
  <section class="bullet-points snap-scrollable">
    <div class="text flipped">
      <div class="container">
        <h3>Free hosting: Get online now!</h3>
        <p>Say goodbye to costly hosting fees with our website building app!</p>
        <p>We offer free hosting to all of our users, making it easy to get your website up and running without breaking the bank. Our reliable hosting service ensures that your website is available 24/7, with no downtime or interruptions.</p>
        <p>With our free hosting, you can focus on creating a stunning website that will attract and engage your audience. Start building your dream website today with our easy-to-use website building app and enjoy the benefits of free hosting!</p>
      </div>
    </div>
    <div>
      <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/website.png?width=600" alt="">
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
<!--        <div class="padding">-->
<!--          <i class="gg-facebook"></i>-->
<!--        </div>-->
<!--        <div class="padding">-->
<!--          <i class="gg-instagram"></i>-->
<!--        </div>-->
<!--        <div class="padding">-->
<!--          <i class="gg-youtube"></i>-->
<!--        </div>-->
      </div>
    </div>
    <div class="columns">
      <div class="column faq">
        <h4>FAQ</h4>
        <div class="question">
          <span>How much does your hosting cost?</span>
          <span>It is free! No paying or subscriptions with limited access.</span>
        </div>
        <div class="question">
          <span>Do I need coding knowledge to use your app?</span>
          <span>No. Our website does not require any coding knowledge.</span>
        </div>
        <div class="question">
          <span>Can I use my own domain name with your app?</span>
          <span>No, but you can choose your own subdomain name.</span>
        </div><div class="question">
          <span>Is your website mobile-responsive?</span>
          <span>Yes. In fact, you can see how your website will look on mobile device in editor.</span>
        </div>
        <div class="question">
          <span>How many websites can I create with your app?</span>
          <span>As many as you want.</span>
        </div>
      </div>
      <div class="column">
        <h4>Contact</h4>
        <span>Email: support@nocoma.com</span>
        <span>Telephone: +420 123 456 789</span>
      </div>
      <div class="column">
        <h4>Legal Information</h4>
        <span>© 2023 Nocoma™. All rights reserved.</span>
        <a href="<?=$GLOBALS["__HOME__"]?>/public/pages/privacy-policy.html" class="lci">Privacy policy</a>
        <a href="<?=$GLOBALS["__HOME__"]?>/public/pages/terms-of-service.html" class="lci">Terms of Service</a>
      </div>
    </div>
  </footer>
</body>
</html>