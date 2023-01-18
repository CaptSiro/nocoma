<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/dotenv/dotenv.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../models/Website.php";
  
  //[website].host/
  $domainRouter = new Router();
  $env = new Env(ENV_FILE);
  
  
  
  /**
   * @param Request $request
   * @param Website $webpage
   * @param Response $response
   * @return void
   */
  function canUserAccess(Request $request, Website $webpage, Response $response): void {
    $isAdmin = $request->session->isset("user")
      && $request->session->get("user")->level === Middleware::LEVEL_ADMIN;
    
    if ($isAdmin) return;
    
    $isNotCreator = !($request->session->isset("user")
      && $request->session->get("user")->ID === $webpage->usersID);
    
    $isCreatorBanned = $isNotCreator && User::isBanned($webpage->usersID)
      ->forwardFailure($response)
      ->getSuccess();
  
    if ($isCreatorBanned) {
      $response->json(["error" => "This creator has been restricted."]);
    }
    
    if ($webpage->isTakenDown && $isNotCreator) {
      $response->json(["error" => "Web page is no longer accessible."]);
    }
  }
  
  $domainRouter->get("/", [function (Request $request, Response $response) use ($env) {
    $website = $request->domain->get("website");
  
    /** @var Website $webpage */
    $webpage = Website::getHomePage($website)->failed(function () use ($website, $response) {
      return Website::getOldestPage($website)->failed(function (Exc $exception) use ($response) {
        $response->render("error", ["message" => $exception->getMessage()]);
      })->getSuccess();
    })->getSuccess();
  
    canUserAccess($request, $webpage, $response);
  
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
  
    canUserAccess($request, $webpage, $response);
  
    $response->render("shell/shell-1", [
      "webpage" => $webpage,
      "user" => $request->session->looselyGet("user")
    ], "php", false);
    $response->readFile(HOSTS_DIR . "/$website/$webpage->src.json", false);
    $response->render("shell/shell-2");
  }], ["source" => "([0-9a-zA-Z_-]+)"]);
  
  
  
  return $domainRouter;