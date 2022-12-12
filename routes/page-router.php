<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/newgen/newgen.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../models/Website.php";
  
  $pageRouter = new Router();
  
  
  
  //TODO: set to POST
  $pageRouter->get("/create", [
    Middleware::requireToBeLoggedIn(Middleware::RESPONSE_JSON),
    function (Request $request, Response $response) {
      $sourceResult = Generate::valid(Generate::string(Generate::CHARSET_URL, 10), Website::isSrcValid());
      $sourceResult->forwardFailure($response);
      $source = $sourceResult->getSuccess();
      
      /** @var User $user */
      $user = $request->session->get("user");
      
      file_put_contents(HOSTS_DIR . "/$user->website/$source.json", '{"type": "WRoot","children": []}');
      Website::create($user->ID, "Untitled website.", $source);
      
      $response->json((object)["document" => $source]);
    }
  ]);
  
  //TODO: set to DELETE
  $pageRouter->get("/delete/:source", [
    Middleware::requireToBeLoggedIn(Middleware::RESPONSE_JSON),
    function (Request $request, Response $response) {
      $response->json(Website::delete($request->param->get("source")));
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