<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/newgen/newgen.php";
//  require_once __DIR__ . "/../lib/dotenv/dotenv.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../models/Website.php";
  require_once __DIR__ . "/../models/Appeal.php";
  require_once __DIR__ . "/../models/Media.php";
  
  $pageRouter = new Router();
//  $env = new Env(ENV_FILE);
  
  $pageRouter->implement(Middleware::requireToBeLoggedIn());
  
  
  
  $pageRouter->get("/:offset", [
    function (Request $request, Response $response) {
      $type = 0;
    
      if ($request->query->isset("type")) {
        $type = intval($request->query->get("type"));
      }
    
      $response->json(Website::getSet(
        $request->session->get("user")->ID,
        intval($request->param->get("offset")),
        $type
      ));
    }
  ], ["offset" => Router::REGEX_NUMBER]);
  
  
  
  $pageRouter->post("/create", [
    function (Request $request, Response $response) {
      /** @var User $user */
      $user = $request->session->get("user");
      
      if (User::isBanned($user->ID)
        ->forwardFailure($response)
        ->getSuccess()) {
        $response->json(["error" => "You are not allowed to create more websites."]);
      }
    
      $source = Generate::valid(Generate::string(Generate::CHARSET_URL, Media::SRC_LENGTH_LASTING), Website::isSRCValid())
        ->forwardFailure($response)
        ->getSuccess();
      
      file_put_contents(HOSTS_DIR . "/$user->website/$source.json", '{"type": "WRoot","areCommentsAvailable":'.json_encode(boolval($request->body->get("areCommentsAvailable"))).',"children":[{"type":"WHeading","level":1,"text":"'.$request->body->get("title").'"}]}');
      $created = Website::getByID(Website::create(
        $user->ID,
        $request->body->get("title"),
        $source,
        intval($request->body->get("isPublic")),
        intval($request->body->get("isHomePage"))
      )->lastInsertedID)
        ->forwardFailure($response)
        ->getSuccess();
      
      $response->json($created);
    }
  ]);
  
  
  
  //TODO: remove delete from url
  $pageRouter->delete("/delete/:source", [
    function (Request $request, Response $response) {
      /** @var User $user */
      $user = $request->session->get("user");
      
      $sourceFile = HOSTS_DIR . "/$user->website/" . $request->param->get("source") . ".json";
    
      if (!file_exists($sourceFile)) {
        $response->setStatusCode(Response::NOT_FOUND);
        $response->json((object) ["error" => "Could not find website to delete."]);
      }

      unlink($sourceFile);
      
      $deleteSideEffect = Website::delete($request->param->get("source"));
      if ($deleteSideEffect->rowCount === 0) {
        $response->setStatusCode(Response::NOT_FOUND);
        $response->json((object) ["error" => "Could not find website to delete."]);
      }
      
      $response->json(["message" => "ok"]);
    }
  ], ["source" => "([0-9a-zA-Z_-]+)"]);
  
  
  
  
  $pageRouter->post("/:source", [
    function (Request $request, Response $response) {
      /** @var User $user */
      $user = $request->session->get("user");
      
      /** @var Website $webpage */
      $webpage = Website::getBySource($user->website, $request->param->get("source"))
        ->forwardFailure($response)
        ->getSuccess();
      
      $sourceFile = HOSTS_DIR . "/$user->website/$webpage->src.json";
      
      if (!file_exists($sourceFile)) {
        $response->setStatusCode(Response::NOT_FOUND);
        $response->json(["error" => "Could not find website to update it's contents."]);
      }
      
      file_put_contents($sourceFile, $request->body->get("content"));
      
      $response->json(["message" => "Successfully updated"]);
    }
  ], ["source" => "([0-9a-zA-Z_-]+)"]);
  
  
  

  $pageRouter->post("/take-down", [
    Middleware::authorize(Middleware::LEVEL_ADMIN),
    function (Request $request, Response $response) {
      $response->json(Website::takeDown(
        intval($request->body->get("id")),
        htmlspecialchars($request->body->get("message"))
      )->forwardFailure($response)->getSuccess());
    }
  ]);
  $pageRouter->delete("/take-down", [
    Middleware::authorize(Middleware::LEVEL_ADMIN),
    function (Request $request, Response $response) {
      $response->json(Website::removeTakeDown(
        intval($request->body->get("id"))
      )->forwardFailure($response)->getSuccess());
    }
  ]);
  
  
  
  $pageRouter->get("/take-down/:id/message/", [function (Request $request, Response $response) {
    $takeDownResult = Website::getTakeDown(intval($request->param->get("id")));
    
    if ($takeDownResult->isFailure()) {
      $response->error($takeDownResult->getFailure()->getMessage(), Response::NOT_FOUND);
    }
    
    $response->send($takeDownResult->getSuccess()->message);
  }], ["id" => Router::REGEX_NUMBER]);
  
  
  
  //TODO: check for usage
  $pageRouter->patch("/isTakenDown/:id/:boolean", [
    Middleware::authorize(Middleware::LEVEL_ADMIN),
    function (Request $request, Response $response) {
      $response->json(Website::set(
        intval($request->param->get("id")),
        "isTakenDown",
        new DatabaseParam("value", intval(
          filter_var($request->param->get("boolean"), FILTER_VALIDATE_BOOLEAN)
        ))
      ));
    }
  ], ["id" => Router::REGEX_NUMBER]);
  
  
  
  $pageRouter->use("/appeal", new RouterPromise(__DIR__ . "/appeal-router.php"));
  
  
  $editableProperties = [
    "title" => ["string", "''"],
    "themesSRC" => ["string", null],
    "thumbnailSRC" => ["string", null]
  ];
  $pageRouter->patch("/", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) use ($editableProperties) {
      $id = $request->body->get("id");
      $property = $request->body->get("property");
      $value = $request->body->looselyGet("value");
      
      if (!is_int($id)) {
        $response->fail(new TypeExc("ID must be integer"));
      }
      
      $websiteObject = Website::getByID(intval($id))
        ->forwardFailure($response)
        ->getSuccess();
      
      if ($websiteObject->usersID !== intval($request->session->get("user")->ID)) {
        $response->fail(new IllegalArgumentExc("You are not authorised to perform this action."));
      }
      
      if (!in_array($property, array_keys($editableProperties))) {
        $response->fail(new IllegalArgumentExc("Property value must be name of editable properties."));
      }
      
      if ($value === null) {
        $response->json(Website::set(
          intval($id),
          $property,
          new DatabaseParam($property, $editableProperties[$property][1], PDO::PARAM_STR)
        ));
      }
      
      if (gettype($value) !== $editableProperties[$property][0]) {
        $response->fail(new TypeExc("Provided value is not of desired type."));
      }
      
      $response->json(Website::set(
        intval($id),
        $property,
        new DatabaseParam(
          $property,
          $value,
          $editableProperties[$property][0] === "integer" ? PDO::PARAM_INT : PDO::PARAM_STR
        )
      ));
    }
  ]);
  
  $visibility = ["public", "private", "planned"];
  $pageRouter->patch("/visibility/:visibility", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      if ($request->param->get("visibility") === "planned") {
        $response->json(Website::setAsPlanned(
          $request->body->get("id"),
          date('Y-m-d H:i:s', strtotime($request->body->looselyGet("releaseDate")))
        ));
      }
      
      Website::removePlannedStatus($request->body->get("id"));
      
      $response->json(Website::set(
        $request->body->get("id"),
        "isPublic",
        new DatabaseParam("isPublic", $request->param->get("visibility") === "public" ? 1 : 0)
      ));
    }
  ], ["visibility" => Router::REGEX_ENUM($visibility)]);
  
  
  $pageRouter->patch("/release-date", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      $response->json(Website::setReleaseDate(
        $request->body->get("id"),
        $request->body->get("releaseDate")
      ));
    }
  ]);
  
  $pageRouter->patch("/home-page/:id/:boolean", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      $response->json(Website::setIsHomePage(
        intval($request->param->get("id")),
        boolval($request->param->get("boolean"))
      ));
    }
  ], [
    "id" => Router::REGEX_NUMBER,
    "boolean" => Router::REGEX_ENUM(["0", "1", "true", "false"])
  ]);
  
  
  
  
  return $pageRouter;