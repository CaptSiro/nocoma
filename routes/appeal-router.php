<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../models/Website.php";
  require_once __DIR__ . "/../models/Appeal.php";
  
  $appealRouter = new Router();
  
  
  
  $appealRouter->post("/", [function (Request $request, Response $response) {
    $response->json(
      Appeal::create($request->body->get("id"), $request->body->looselyGet("message"))
        ->forwardFailure($response)
        ->getSuccess()
    );
  }]);
  
  
  
  $appealRouter->patch("/:id", [
    Middleware::authorize(Middleware::LEVEL_ADMIN),
    function (Request $request, Response $response) {
      $response->json(
        Appeal::setAsRead($request->param->get("id"))
      );
    }
  ], ["id" => Router::REGEX_NUMBER]);
  
  
  
  $appealRouter->delete("/:id/accept", [
    Middleware::authorize(Middleware::LEVEL_ADMIN),
    function (Request $request, Response $response) {
      $response->json(
        Appeal::accept($request->param->get("id"))
          ->forwardFailure($response)
          ->getSuccess()
      );
    }
  ], ["id" => Router::REGEX_NUMBER]);
  
  
  
  $appealRouter->delete("/:id/decline", [
    Middleware::authorize(Middleware::LEVEL_ADMIN),
    function (Request $request, Response $response) {
      $response->json(
        Appeal::decline($request->param->get("id"))
          ->forwardFailure($response)
          ->getSuccess()
      );
    }
  ], ["id" => Router::REGEX_NUMBER]);
  
  
  
  $appealRouter->get("/:offset", [function (Request $request, Response $response) {
    $response->json(
      Appeal::getSet(
        $request->param->get("offset"),
        intval($request->query->looselyGet("type", 0))
      )
    );
  }], ["offset" => Router::REGEX_NUMBER]);
  
  
  
  return $appealRouter;