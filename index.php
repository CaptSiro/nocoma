<?php
  
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
  
  
  $router->onErrorEvent(function ($message, Request $request, Response $response) {
    $response->render("error", ["message" => $message]);
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
  
  
  
  
  $router->domain("[website].$hostName", require __DIR__ . "/routes/domain-router.php", []);
  
  
  
  
//  $router->showTrace();
  $router->serve();