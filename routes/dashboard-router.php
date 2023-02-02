<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/newgen/newgen.php";
  require_once __DIR__ . "/../lib/dotenv/dotenv.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../models/Website.php";
  
  $dashboardRouter = new Router();
  $env = new Env(ENV_FILE);
  
  $dashboardRouter->implement(Middleware::requireToBeLoggedIn(Middleware::RESPONSE_REDIRECT));
  
  
  
  $dashboardRouter->get("/", [
    function (Request $request, Response $response) {
      /** @var User $user */
      $user = $request->session->get("user");
      if ($user->level === Middleware::LEVEL_ADMIN) {
        $response->redirect("/dashboard/admin");
      }
      
      $response->redirect("/dashboard/user");
    }
  ]);
  
  
  
  
  
  $dashboardRouter->get("/user", [
    Middleware::authorize(Middleware::LEVEL_USER, Middleware::RESPONSE_REDIRECT, Middleware::RESPONSE_REDIRECT_DASHBOARD_MAP),
    function (Request $request, Response $response) use ($env) {
      $response->render("dashboards/user", [
        "user" => $request->session->get("user"),
        "env_home" => $env->get("HOST_NAME")
          ->forwardFailure($response)
          ->getSuccess(),
        "protocol" => $request->protocol
      ]);
    }
  ]);
  
  
  
  
  
  $dashboardRouter->get("/admin", [
    Middleware::authorize(Middleware::LEVEL_ADMIN, Middleware::RESPONSE_REDIRECT, Middleware::RESPONSE_REDIRECT_DASHBOARD_MAP),
    function (Request $request, Response $response) use ($env) {
      $response->render("dashboards/admin", [
        "user" => $request->session->get("user"),
        "env_home" => $env->get("HOST_NAME")
          ->forwardFailure($response)
          ->getSuccess(),
        "protocol" => $request->protocol
      ]);
    }
  ]);
  
  
  
  
  return $dashboardRouter;