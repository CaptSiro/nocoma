<?php
  
  require_once __DIR__ . "/routes/Middleware.php";
  Middleware::setDefaultResponseType(Middleware::RESPONSE_JSON);
  
  
  
  require_once __DIR__ . "/lib/dotenv/dotenv.php";
  $env = new Env(__DIR__ . "/.env");
  $hostName = $env->get("HOST_NAME")->failed(function () {
    exit("Error code: 0x000003");
  })->getSuccess();
  
  
  
  /** @var HomeRouter $router */
  $router = require __DIR__ . "/lib/routepass/routepass.php";
  
  
  
  $router->setBodyParser(HomeRouter::BODY_PARSER_JSON());
  $router->setFlag(HomeRouter::FLAG_MAIN_SERVER_HOST_NAME, $hostName);
  $router->setFlag(HomeRouter::FLAG_SESSION_COOKIE_PARAMS, [0, "/", ".$hostName"]);
  $router->setViewDirectory(__DIR__ . "/views");
  
  
  
  $router->static("/public", __DIR__ . "/static");
  
  
  
  $router->onAnyErrorEvent(function (RequestError $requestError) {
//    var_dump($requestError->message);
    $requestError->response->render("error", ["message" => htmlspecialchars($requestError->message)]);
  });
  
  
  
  $router->get("/", [function (Request $request, Response $response) {
    $response->render("nocoma");
  }]);
  
  
  
  
//  $router->get("/tinter", [function (Request $request, Response $response) {
//    require_once __DIR__ . "/models/DynamicTheme.php";
//
//    $response->json(
//      ["src" => DynamicTheme::createFrom(
//        "My theme#1",
//        "3ij5yl74WZ",
//        "__test-bot__", 4, 2)
//        ->forwardFailure($response)
//        ->getSuccess()]
//    );
//  }]);
  
  
  
  $router->get("/favicon.ico", [function (Request $request, Response $response) {
    $response->readFile(__DIR__ . "/static/images/nocoma-icon.ico");
  }]);
  
  
//  $router->get("/hash", [function () {
//    foreach (
//      array_map(function ($path) { return [sha1_file(__DIR__ . "/static/css/themes/" . $path), $path]; }, array_diff(scandir(__DIR__ . "/static/css/themes"), [".", ".."]))
//      as $hash
//    ) {
//      echo "$hash[0]: $hash[1]<br>";
//    }
//  }]);
  
  
  
//  $router->get("/server", [function () {
//    foreach ($_SERVER as $key => $value) {
//      echo "'$key' => $value<br>";
//    }
//    exit;
//  }]);
  
  
  
  $router->get("/error", [function (Request $request, Response $response) {
    $response->render("error", ["message" => $request->query->get("message")]);
  }]);
  
  
  
//  $router->get("/inspector", [function (Request $request, Response $response) {
//    $response->render("inspector");
//  }]);
  
  
  
  $router->use("/auth", new RouterPromise(__DIR__ . "/routes/auth-router.php"));
  $router->use("/dashboard", new RouterPromise(__DIR__ . "/routes/dashboard-router.php"));
  $router->use("/editor", new RouterPromise(__DIR__ . "/routes/editor-router.php"));
  $router->use("/bundler", new RouterPromise(__DIR__ . "/routes/bundler-router.php"));
  $router->use("/page", new RouterPromise(__DIR__ . "/routes/page-router.php"));
  $router->use("/file", new RouterPromise(__DIR__ . "/routes/file-router.php"));
  $router->use("/profile", new RouterPromise(__DIR__ . "/routes/profile-router.php"));
  $router->use("/users", new RouterPromise(__DIR__ . "/routes/users-router.php"));
  $router->use("/comments", new RouterPromise(__DIR__ . "/routes/comments-router.php"));
  $router->use("/theme", new RouterPromise(__DIR__ . "/routes/theme-router.php"));
  
  
  
  /** @var Blueprints $b */
  //  $b = require __DIR__ . "/lib/blueprint/collection.php";
//  $router->get("/blueprint", [function (Request $request, Response $response) use ($b) {
//    $boolean = $b->boolean()->falsy();
//    var_dump($boolean->parse(true));
//    var_dump($boolean->parse("false"));
//    var_dump($boolean->parse(null));
//    var_dump($boolean->parse(false));
//  }]);
  
  
  
  //TODO: remove or incorporate
//  require_once __DIR__ . "/lib/scuffed-sockets/scuffed-sockets.php";
//  $router->get("/stream-page", [function (Request $request, Response $response) {
//    $response->render("stream");
//  }]);
//
//  $router->get("/stream/broadcast", [function (Request $request, Response $response) {
//    Server::broadcast(new Packet($request->query->looselyGet("data", "pong"), "message"));
//  }]);
//
//  $router->get("/stream", [function (Request $request, Response $response) {
//    header("Cache-Control: no-store");
//    header("Content-Type: text/event-stream");
//    session_write_close();
//
//    $id = $request->query->get("id");
//    Server::accept($id);
//    $send = function ($packet) {
//      echo("event: $packet->event\n");
//      echo("data: $packet->data\n");
//      echo("\n");
//
//      if (ob_get_contents()) ob_end_flush();
//      flush();
//    };
//    $send(new Packet("accepted $id", "connected"));
//
//    ignore_user_abort(false);
//
//    while (true) {
//      /** @var ServerSocket $socket */
//      $socket = Server::getSocket($id)
//        ->forwardFailure($response)
//        ->getSuccess();
//
//      $packets = $socket->getData();
//      foreach ($packets as $packet) {
//        $send($packet);
//      }
//
//      echo ".\n";
//      if (ob_get_contents()) ob_end_flush();
//      flush();
//      if (connection_status() != 0) {
//        Server::broadcast(new Packet("user $id has left us :(", "disconnected"));
//        break;
//      }
//
//      usleep(500000);
//    }
//
//    Server::destroy($id);
//  }]);
  
  
  $router->get("/robots.txt", [function (Request $request, Response $response) {
    $response->setHeader("Content-Type", "text/plain");
    $response->send("User-agent: *\nAllow: /auth/");
  }]);
  
  $router->domain("[website].$hostName", new RouterPromise(__DIR__ . "/routes/domain-router.php"), [
    "website" => Router::REGEX_BASE64_URL_SAFE
  ]);
  
  
  
//  $router->showTrace();
  $router->serve();