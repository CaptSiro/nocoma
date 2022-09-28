<?php
  // exit("Locked down");

  require_once __DIR__ . "/../../lib/rekves/rekves.php";
  require_once __DIR__ . "/../../lib/wall/wall.php";

  const BRICK_DIR = "C:\\wamp\\www\\nocoma\\public\\bricks";


  $packeger = new Packager();


  // var_dump(explode(",", $req->body->class));
  foreach (explode(",", $req->body->directory) as $dir) {
    $path = BRICK_DIR . "/$dir/comp.xml";
    if (file_exists($path)) {
      var_dump($packeger->register($path)->isFailure());
    }
  }