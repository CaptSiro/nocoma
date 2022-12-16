<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/newgen/newgen.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../models/Website.php";
  
  $pageRouter = new Router();
  
  
  
  $pageRouter->get("/:offset", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      $response->json(Website::getSet($request->session->get("user")->ID, intval($request->param->get("offset"))));
    }
  ], ["offset" => Router::REGEX_NUMBER]);
  
  
  
  $pageRouter->post("/create", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      $sourceResult = Generate::valid(Generate::string(Generate::CHARSET_URL, 10), Website::isSRCValid());
      $sourceResult->forwardFailure($response);
      $source = $sourceResult->getSuccess();
      
      /** @var User $user */
      $user = $request->session->get("user");
      
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
    Middleware::requireToBeLoggedIn(),
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