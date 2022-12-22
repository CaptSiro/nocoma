<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../models/Media.php";
  
  $fileRouter = new Router();
  
  
  
  
  
  $fileRouter->get("/:offset", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      $response->json(Media::getSet(
        $request->session->get("user")->ID,
        intval($request->param->get("offset")),
        $request->query->looselyGet("order", "0")
      ));
    }
  ], ["offset" => Router::REGEX_NUMBER]);
  
  
  
  
  
  $fileRouter->get("/collect", [
    Middleware::requireToBeLoggedIn(Middleware::RESPONSE_REDIRECT),
    function (Request $request, Response $response) {
      $response->render("collect");
    }
  ]);
  
  
  
  
  
  $fileRouter->post("/collect", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      if (!is_array($request->files->get("uploaded"))) {
        $response->json(["error" => "Uploaded files must be in array."]);
      }
      
      foreach ($request->files->get("uploaded") as $file) {
        Media::save($file, $request->session->get("user"))
            ->forwardFailure($response);
      }
      
      $response->json(["message" => "ok"]);
    }
  ]);
  
  
  
  
  
  $fileRouter->delete("/:file", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      $response->json(
        Media::delete($request->param->get("file"), $request->session->get("user"))
          ->forwardFailure($response)
          ->getSuccess()
      );
    }
  ], ["file" => Router::REGEX_BASE64_URL_SAFE]);
  
  
  
  
  
  $fileRouter->patch("/:file", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      $response->json(Media::rename($request->param->get("file"), $request->body->get("value")));
    }
  ], ["file" => Router::REGEX_BASE64_URL_SAFE]);
  
  
  
  
  
  return $fileRouter;