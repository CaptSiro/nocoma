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
  
  function renderShell (Request $request, Response $response, Website $webpage, string $website) {
    $user = null;
    if ($request->session->isset("user")) {
      $user = clone $request->session->get("user");
      $user->stripPrivate();
    }
  
    $response->render("shell/shell-1", [
      "webpage" => $webpage,
      "user" => $user
    ], "php", false);
    $response->readFileSafe(HOSTS_DIR . "/$website/$webpage->src.json", false);
    $response->render("shell/shell-2");
  }
  $domainRouter->get("/", [function (Request $request, Response $response) use ($env) {
    $website = $request->domain->get("website");
  
    /** @var Website $webpage */
    $webpage = Website::getHomePage($website)
      ->renderError($response, ["message" => "No website is set to homepage."])
      ->getSuccess();
    
//    ->failed(function () use ($website, $response) {
//      return Website::getOldestPage($website)->failed(function (Exc $exception) use ($response) {
//        $response->render("error", ["message" => $exception->getMessage()]);
//      })->getSuccess();
//    })->getSuccess();
    
    if (!Website::isAccessible($webpage)) {
      $response->render("error", ["message" => "Homepage is private."]);
    }
    
    canUserAccess($request, $webpage, $response);
  
    renderShell($request, $response, $webpage, $website);
  }]);
  
  
  
  $domainRouter->get("/:source", [function (Request $request, Response $response) {
    $website = $request->domain->get("website");
  
    /** @var Website $renderedPage */
    $webpage = Website::getBySource($website, $request->param->get("source"))
      ->renderError($response, ["message" => "There is no website for this url.<br>Check if the url is correct or the website no longer exists."])
      ->getSuccess();
  
    if (!Website::isAccessible($webpage)) {
      $response->render("error", ["message" => "Website is private."]);
    }
  
    canUserAccess($request, $webpage, $response);
  
    renderShell($request, $response, $webpage, $website);
  }], ["source" => "([0-9a-zA-Z_-]+)"]);
  
  
  
  return $domainRouter;