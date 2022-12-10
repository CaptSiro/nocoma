<?php

  require_once __DIR__ . "/../lib/routepass/routers.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  $editorRouter = new Router();
  
  
  
  
  
  $editorRouter->get("/", [
    Middleware::requireToBeLoggedIn(Middleware::RESPONSE_REDIRECT),
    function (Request $request, Response $response) {
      $response->render("editor");
    }
  ]);
  
  
  
  
  
  return $editorRouter;