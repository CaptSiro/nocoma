<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/regular-expressions.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/User.php";
  
  $profileRouter = new Router();
  
  
  
  
  $profileRouter->patch("/username", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      if (!preg_match(REGEX_USERNAME, $request->body->get("value"))) {
        $response->json(["error" => "Not a valid username."]);
      }
  
      $sideEffect = User::updateUsername($request->body->get("value"), $request->session->get("user")->ID);
      if ($sideEffect->rowCount === 1) {
        $request->session->modify("user", function (User $user) use ($request) {
          $user->username = $request->body->get("value");
          return $user;
        });
      }
      
      $response->json($sideEffect);
    }
  ]);
  
  
  
  
  return $profileRouter;