<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../models/Website.php";
  
  $userRouter = new Router();
  
  
  
  $userRouter->get("/:offset", [
    Middleware::requireToBeLoggedIn(),
    Middleware::authorize(Middleware::LEVEL_ADMIN),
    function (Request $request, Response $response) {
      $response->json(User::getSet($request->param->get("offset")));
    }
  ], ["offset" => Router::REGEX_NUMBER]);
  
  
  
  $userRouter->get("/:userID/:offset", [
    Middleware::requireToBeLoggedIn(),
    Middleware::authorize(Middleware::LEVEL_ADMIN),
    function (Request $request, Response $response) {
      $response->json(Website::getSet(
        $request->param->get("userID"),
        $request->param->get("offset")
      ));
    }
  ], ["userID" => Router::REGEX_NUMBER, "offset" => Router::REGEX_NUMBER]);
  
  
  
  return $userRouter;