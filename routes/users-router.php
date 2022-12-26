<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../models/Website.php";
  
  $userRouter = new Router();
  $userRouter->implement(
    Middleware::requireToBeLoggedIn(),
    Middleware::authorize(Middleware::LEVEL_ADMIN)
  );
  
  
  
  $typeRestrictions = [
    "",
    " AND `" . User::TABLE_NAME . "`.`isDisabled` = 1"
  ];
  $userRouter->get("/:offset", [
    function (Request $request, Response $response) use ($typeRestrictions) {
      $restrictions = "level != " . Middleware::LEVEL_ADMIN
        . ($request->query->isset("type")
          ? $typeRestrictions[intval($request->query->get("type"))]
          : "");
    
      $response->json(
        User::getSet(
          $request->param->get("offset"),
          $restrictions
        )
      );
    }
  ], ["offset" => Router::REGEX_NUMBER]);
  
  
  
  $userRouter->get("/:userID/:offset", [
    function (Request $request, Response $response) {
      $response->json(Website::getSet(
        $request->param->get("userID"),
        $request->param->get("offset")
      ));
    }
  ], ["userID" => Router::REGEX_NUMBER, "offset" => Router::REGEX_NUMBER]);
  
  
  
  $userRouter->patch("/isDisabled/:id/:boolean", [
    function (Request $request, Response $response) {
      $response->json(User::set(
        intval($request->param->get("id")),
        "isDisabled",
        new DatabaseParam("value", intval(
          filter_var($request->param->get("boolean"), FILTER_VALIDATE_BOOLEAN)
        ))
      ));
    }
  ], ["id" => Router::REGEX_NUMBER]);
  
  
  
  return $userRouter;