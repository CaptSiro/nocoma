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

  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/components/Renderer3D.js"></script>
  <script src="<?= $GLOBALS["__HOME__"] ?>/public/js/nocoma.js" defer></script>

  <link href='https://css.gg/chevron-double-down.css' rel='stylesheet'>
  <link href='https://css.gg/facebook.css' rel='stylesheet'>
  <link href='https://css.gg/youtube.css' rel='stylesheet'>
  <link href='https://css.gg/instagram.css' rel='stylesheet'>
</head>
<body>
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
        <h3>Lorem ipsum dolor sit amet.</h3>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Placeat eaque sapiente odit possimus quaerat iste praesentium ad quia, corrupti, tempore maiores quis id vel soluta alias magni, nulla optio consequuntur?</p>
        <p>Labore animi perspiciatis ipsa sed quisquam odit, assumenda fugiat, eveniet harum maiores excepturi omnis facilis, quas distinctio repellat consequatur totam a voluptatem aspernatur at velit saepe nulla. Nihil, sequi accusamus!</p>
        <p>Possimus, nobis dicta ratione dolore reprehenderit totam qui itaque esse ut quisquam magnam minus, laudantium culpa odio quaerat! Quas cumque blanditiis ex soluta consectetur obcaecati. Saepe ut impedit quis modi!</p>
      </div>
    </div>
    <div>
      <img src="<?= $GLOBALS["__HOME__"] ?>/public/images/theme-stock-pictures/website.png" alt="">
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