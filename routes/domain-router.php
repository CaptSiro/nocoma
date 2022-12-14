<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/dotenv/dotenv.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/../models/User.php";
  
  //[website].host/
  $domainRouter = new Router();
  $env = new Env(ENV_FILE);
  
  
  
  $domainRouter->get("/", [function (Request $request, Response $response) use ($env) {
    $website = $request->domain->get("website");
    
    /** @var Website $renderedPage */
    $webpage = Website::getHomePage($website)->failed(function () use ($website, $response) {
      return Website::getOldestPage($website)->failed(function (Exc $exception) use ($response) {
        $response->render("error", ["message" => $exception->getMessage()]);
      })->getSuccess();
    })->getSuccess();
    
    $hostName = $env->get("HOST_NAME")->failed(function () use ($response) {
      $response->render("error", ["message" => "505: Internal server error.<br><br>Error code: 0x000003"]);
    })->getSuccess();
    
    $response->render("shell/shell-1", ["SERVER_HOME" => $request->protocol . "://$hostName$_SERVER[HOME_DIR]", "webpage" => $webpage], "php", false);
    $response->readFile(HOSTS_DIR . "/$website/$webpage->src.json", false);
    $response->render("shell/shell-2");
  }]);
  
  
  
  
  $domainRouter->get("/:source", [function (Request $request, Response $response) {
    $website = $request->domain->get("website");
  
    /** @var Website $renderedPage */
    $webpage = Website::getBySource($website, $request->param->get("source"))->failed(function (Exc $exception) use ($response) {
      $response->render("error", ["message" => $exception->getMessage()]);
    })->getSuccess();
    
    $response->render("shell/shell-1", ["webpage" => $webpage], "php", false);
    $response->readFile(HOSTS_DIR . "/$website/$webpage->src.json", false);
    $response->render("shell/shell-2");
  }], ["source" => "([0-9a-zA-Z_-]+)"]);
  
  
  
  return $domainRouter;