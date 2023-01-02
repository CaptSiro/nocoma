<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/newgen/newgen.php";
//  require_once __DIR__ . "/../lib/dotenv/dotenv.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../models/Website.php";
  
  $pageRouter = new Router();
//  $env = new Env(ENV_FILE);
  
  $pageRouter->implement(Middleware::requireToBeLoggedIn());
  
  
  
  $pageRouter->get("/:offset", [
    function (Request $request, Response $response) {
      $response->json(Website::getSet($request->session->get("user")->ID, intval($request->param->get("offset"))));
    }
  ], ["offset" => Router::REGEX_NUMBER]);
  
  
  
  $pageRouter->post("/create", [
    function (Request $request, Response $response) {
      /** @var User $user */
      $user = $request->session->get("user");
      
      if (User::isBanned($user->ID)
        ->forwardFailure($response)
        ->getSuccess()) {
        $response->json(["error" => "You are not allowed to create more websites."]);
      }
    
      $source = Generate::valid(Generate::string(Generate::CHARSET_URL, 10), Website::isSRCValid())
        ->forwardFailure($response)
        ->getSuccess();
      
      file_put_contents(HOSTS_DIR . "/$user->website/$source.json", '{"type": "WRoot","children": []}');
      $created = Website::getByID(Website::create(
        $user->ID,
        $request->body->get("title"),
        $source,
        intval($request->body->get("isPublic")),
        intval($request->body->get("isHomePage")),
        intval($request->body->get("areCommentsAvailable"))
      )->lastInsertedID)
        ->forwardFailure($response)
        ->getSuccess();
      
      $response->json($created);
    }
  ]);
  
  
  
  $pageRouter->delete("/delete/:source", [
    function (Request $request, Response $response) {
      /** @var User $user */
      $user = $request->session->get("user");
      
      $sourceFile = HOSTS_DIR . "/$user->website/" . $request->param->get("source") . ".json";
    
      if (!file_exists($sourceFile)) {
        $response->setStatusCode(Response::NOT_FOUND);
        $response->json((object) ["error" => "Could not find website to delete."]);
      }

      unlink($sourceFile);
      
      $deleteSideEffect = Website::delete($request->param->get("source"));
      if ($deleteSideEffect->rowCount === 0) {
        $response->setStatusCode(Response::NOT_FOUND);
        $response->json((object) ["error" => "Could not find website to delete."]);
      }
      
      $response->json(["message" => "ok"]);
    }
  ], ["source" => "([0-9a-zA-Z_-]+)"]);
  
  
  

  $pageRouter->post("/take-down", [
    Middleware::authorize(Middleware::LEVEL_ADMIN),
    function (Request $request, Response $response) {
      $response->json(Website::takeDown(
        intval($request->body->get("id")),
        htmlspecialchars($request->body->get("message"))
      )->forwardFailure($response)->getSuccess());
    }
  ]);
  $pageRouter->delete("/take-down", [
    Middleware::authorize(Middleware::LEVEL_ADMIN),
    function (Request $request, Response $response) {
      $response->json(Website::removeTakeDown(
        intval($request->body->get("id"))
      )->forwardFailure($response)->getSuccess());
    }
  ]);
  
  
  
  //TODO: check for usage
  $pageRouter->patch("/isTakenDown/:id/:boolean", [
    Middleware::authorize(Middleware::LEVEL_ADMIN),
    function (Request $request, Response $response) {
      $response->json(Website::set(
        intval($request->param->get("id")),
        "isTakenDown",
        new DatabaseParam("value", intval(
          filter_var($request->param->get("boolean"), FILTER_VALIDATE_BOOLEAN)
        ))
      ));
    }
  ], ["id" => Router::REGEX_NUMBER]);
  
  
  
  //TODO: set to PATCH
  //TODO: refactor to -> /set/:class/:function/:argument
//  $pageRouter->get("/set-as-home-page/:websiteID", [
//    Middleware::requireToBeLoggedIn(Middleware::RESPONSE_JSON),
//    function (Request $request, Response $response) {
//      var_dump(Website::setAsHomePage($request->param->get("websiteID")));
//      $response->end();
//    }
//  ], ["websiteID" => Router::REGEX_NUMBER]);
  
  
  
  
  return $pageRouter;