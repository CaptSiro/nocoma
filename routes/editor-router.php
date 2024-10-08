<?php

  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/dotenv/dotenv.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../models/Website.php";
  
  $editorRouter = new Router();
  $env = new Env(ENV_FILE);
  
  
  
  
  
  $editorRouter->get("/:source", [
    Middleware::requireToBeLoggedIn(Middleware::RESPONSE_REDIRECT),
    function (Request $request, Response $response) use ($env) {
      /** @var User $user */
      $user = $request->session->get("user");
      $filePath = HOSTS_DIR . "/$user->website/" . $request->param->get("source") . ".json";
      
      if (!file_exists($filePath)) {
        $response->render("error", ["message" => "Requested page does not exists."]);
      }
      
      $webpage = Website::getBySource($user->website, $request->param->get("source"))
        ->forwardFailure($response)
        ->getSuccess();
      
      $user = null;
      if ($request->session->isset("user")) {
        $user = clone $request->session->get("user");
        $user->stripPrivate();
      }
      
      $response->generateHeaders();
      $response->render("editor/editor-1", [
        "webpage" => $webpage,
        "user" => $user,
        "postLink" => "$request->protocol://$user->website."
          . $env->get("HOST_NAME")
            ->forwardFailure($response)
            ->getSuccess()
          . Response::createRedirectURLDirPrefix("/" . $request->param->get("source"))
      ], "php", false);
      $response->readFileSafe($filePath, false);
      $response->render("editor/editor-2");
    }
  ], ["source" => Router::REGEX_BASE64_URL_SAFE]);
  
  
  
  
  
  return $editorRouter;