<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  $dashboardRouter = new Router();
  
  
  
  
  $dashboardRouter->get("/", [
    Middleware::requireToBeLoggedIn(Middleware::RESPONSE_REDIRECT),
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
    Middleware::requireToBeLoggedIn(Middleware::RESPONSE_REDIRECT),
    Middleware::authorize(Middleware::LEVEL_USER, Middleware::RESPONSE_REDIRECT, Middleware::RESPONSE_REDIRECT_DASHBOARD_MAP),
    function (Request $request, Response $response) {
      $response->render("dashboards/user");
    }
  ]);
  
  
  
  
  
  $dashboardRouter->get("/admin", [
    Middleware::requireToBeLoggedIn(Middleware::RESPONSE_REDIRECT),
    Middleware::authorize(Middleware::LEVEL_ADMIN, Middleware::RESPONSE_REDIRECT, Middleware::RESPONSE_REDIRECT_DASHBOARD_MAP),
    function (Request $request, Response $response) {
      $response->send("dashboards/admin");
    }
  ]);
  
  
  
  
  return $dashboardRouter;