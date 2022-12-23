<?php
  
  require_once __DIR__ . "/routes/Middleware.php";
  Middleware::setDefaultResponseType(Middleware::RESPONSE_JSON);
  
  
  require_once __DIR__ . "/lib/dotenv/dotenv.php";
  $env = new Env(__DIR__ . "/.env");
  $hostName = $env->get("HOST_NAME")->failed(function () {
    exit("HOST_NAME is not set.");
  })->getSuccess();
  
  /** @var HomeRouter $router */
  $router = require __DIR__ . "/lib/routepass/routepass.php";
  
  $router->setBodyParser(HomeRouter::BODY_PARSER_JSON());
  $router->setFlag(HomeRouter::FLAG_MAIN_SERVER_HOST_NAME, $hostName);
  $router->setViewDirectory(__DIR__ . "/views");
  
  
  $router->static("/public", __DIR__ . "/static");
  
  
  $router->onAnyErrorEvent(function (RequestError $requestError) {
    $requestError->response->render("error", ["message" => htmlspecialchars($requestError->message)]);
  });
  
  
  $router->get("/", [function (Request $request, Response $response) {
    $response->render("nocoma");
  }]);
  
  
  
  
  $router->get("/hash", [function (Request $request, Response $response) {
    $response->send(password_hash($request->query->get("value"), PASSWORD_DEFAULT));
  }]);
  
  
  
  
  $router->use("/auth", require __DIR__ . "/routes/auth-router.php");
  $router->use("/dashboard", require __DIR__ . "/routes/dashboard-router.php");
  $router->use("/editor", require __DIR__ . "/routes/editor-router.php");
  $router->use("/bundler", require __DIR__ . "/routes/bundler-router.php");
  $router->use("/page", require __DIR__ . "/routes/page-router.php");
  $router->use("/file", require __DIR__ . "/routes/file-router.php");
  $router->use("/profile", require __DIR__ . "/routes/profile-router.php");
  $router->use("/users", require __DIR__ . "/routes/user-router.php");
  
  /** @var Blueprints $b */
  $b = require __DIR__ . "/lib/blueprint/collection.php";
  $router->get("/blueprint", [function (Request $request, Response $response) use ($b) {
    $boolean = $b->boolean()->falsy();
    var_dump($boolean->parse(true));
    var_dump($boolean->parse("false"));
    var_dump($boolean->parse(null));
    var_dump($boolean->parse(false));
  }]);
  
  
  
  //TODO: remove or incorporate
  require_once __DIR__ . "/lib/scuffed-sockets/scuffed-sockets.php";
  $router->get("/stream-page", [function (Request $request, Response $response) {
    $response->render("stream");
  }]);
  
  $router->get("/stream/broadcast", [function (Request $request, Response $response) {
    Server::broadcast(new Packet($request->query->looselyGet("data", "pong"), "message"));
  }]);
  
  $router->get("/stream", [function (Request $request, Response $response) {
    header("Cache-Control: no-store");
    header("Content-Type: text/event-stream");
    session_write_close();
    
    $id = $request->query->get("id");
    Server::accept($id);
    $send = function ($packet) {
      echo("event: $packet->event\n");
      echo("data: $packet->data\n");
      echo("\n");
  
      if (ob_get_contents()) ob_end_flush();
      flush();
    };
    $send(new Packet("accepted $id", "connected"));
    
    ignore_user_abort(false);
    
    while (true) {
      /** @var ServerSocket $socket */
      $socket = Server::getSocket($id)
        ->forwardFailure($response)
        ->getSuccess();
      
      $packets = $socket->getData();
      foreach ($packets as $packet) {
        $send($packet);
      }
      
      echo ".\n";
      if (ob_get_contents()) ob_end_flush();
      flush();
      if (connection_status() != 0) {
        Server::broadcast(new Packet("user $id has left us :(", "disconnected"));
        break;
      }
  
      usleep(500000);
    }
    
    Server::destroy($id);
  }]);
  
  
  
  
  $router->domain("[website].$hostName", require __DIR__ . "/routes/domain-router.php", []);
  
  
  
  
//  $router->showTrace();
  $router->serve();