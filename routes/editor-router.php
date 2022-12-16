<?php

  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../models/Website.php";
  
  $editorRouter = new Router();
  
  
  
  
  
  $editorRouter->get("/:file", [
    Middleware::requireToBeLoggedIn(Middleware::RESPONSE_REDIRECT),
    function (Request $request, Response $response) {
      /** @var User $user */
      $user = $request->session->get("user");
      $filePath = HOSTS_DIR . "/$user->website/" . $request->param->get("file") . ".json";
      
      if (!file_exists($filePath)) {
        //TODO: change to error view
        $response->error("Requested page does not exists.", Response::NOT_FOUND);
      }
      
      $webpage = Website::getBySource($user->website, $request->param->get("file"), true)
        ->forwardFailure($response)
        ->getSuccess();
      
      $response->generateHeaders();
      $response->render("editor/editor-1", ["webpage" => $webpage], "php", false);
      $response->readFile($filePath, false);
      $response->render("editor/editor-2");
    }
  ], ["file" => Router::REGEX_BASE64_URL_SAFE]);
  
  
  
  
  
  return $editorRouter;