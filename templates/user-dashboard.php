<?php
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../lib/rekves/rekves.php";
  require_once __DIR__ . "/../lib/authenticate.php";

  Auth::redirect($req->session->get("user"), [AUTH_USER], AUTH_DEFAULT_REDIRECT_MAP);
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - NoComa</title>

  <link rel="stylesheet" href="../public/css/main.css">
  <link rel="stylesheet" href="../public/css/dashboard.css">
</head>
<body>
  <!-- <a href="../ajax/get/delete-logout.php">&lt;-</a> -->
  <nav>
    <section>
      <a href="">An</a>
      <a href="">Po</a>
      <a href="">Co</a>
      <a href="">Th</a>
      <a href="">Ga</a>
      <a href="">Pr</a>
    </section>
    <section>
      <a href="">&lt;-</a>
    </section>
  </nav>
  <main>
    <section>
      <header>
        <button>Some stuff</button>
        <button>More stuff</button>
      </header>
      <div class="scrollable">
        <p>Long ass text</p>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Necessitatibus, qui autem. Eveniet tempora optio perspiciatis omnis unde beatae quam laborum, cupiditate quasi similique sapiente possimus consequatur id consectetur dicta qui!</p>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Necessitatibus, qui autem. Eveniet tempora optio perspiciatis omnis unde beatae quam laborum, cupiditate quasi similique sapiente possimus consequatur id consectetur dicta qui!</p>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Necessitatibus, qui autem. Eveniet tempora optio perspiciatis omnis unde beatae quam laborum, cupiditate quasi similique sapiente possimus consequatur id consectetur dicta qui!</p>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Necessitatibus, qui autem. Eveniet tempora optio perspiciatis omnis unde beatae quam laborum, cupiditate quasi similique sapiente possimus consequatur id consectetur dicta qui!</p>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Necessitatibus, qui autem. Eveniet tempora optio perspiciatis omnis unde beatae quam laborum, cupiditate quasi similique sapiente possimus consequatur id consectetur dicta qui!</p>
      </div>
    </section>
    <section>

    </section>
  </main>
</body>
</html>