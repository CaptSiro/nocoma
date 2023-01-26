<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/newgen/newgen.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/Theme.php";
  require_once __DIR__ . "/../models/User.php";
  
  $themeRouter = new Router();
  
  
  
  $themeRouter->options("/:src", [Middleware::corsAllowAll("GET")]);
  $themeRouter->get("/:src", [function (Request $request, Response $response) {
    $src = $request->param->get("src");
    if ($src[0] === ".") {
    
    }
  }]);
  
  
  
  $themeRouter->delete("/:src", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      $response->json(Theme::delete($request->param->get("src")));
    }
  ]);
  
  
  
  $themeRouter->post("/", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      /**
       * @type RequestFile $themeFile
       */
      $themeFile = $request->files->get("theme");
      if ($themeFile->error !== UPLOAD_ERR_OK) {
        fail(new InvalidArgumentExc("Error when uploading file: '$themeFile->fullName'. UPLOAD_ERROR: '$themeFile->error'"))
          ->forwardFailure($response);
      }
  
      /**
       * @type User $user
       */
      $user = $request->session->get("user");
      
      $source = Generate::valid(
        Generate::string(Generate::CHARSET_URL, 10),
        Theme::isSRCValid(HOSTS_DIR . "/$user->website/media/")
      )->forwardFailure($response)->getSuccess();
      
      $footprint = sha1_file($themeFile->temporaryName);
      if (!Theme::isUnique($footprint, $user->ID)) {
        fail(new NotUniqueValueExc("This theme already exists on the server."))
          ->forwardFailure($response);
      }
      
      if ($themeFile->ext !== ".css" || !preg_match("/^text/", mime_content_type($themeFile->temporaryName))) {
        fail(new InvalidArgumentExc("File type is not supported."))
          ->forwardFailure($response);
      }
      
      $themeFile->moveTo(HOSTS_DIR . "/$user->website/media/$source$themeFile->ext")
        ->forwardFailure($response);
      
      $response->json(Database::get()->statement(
        "INSERT INTO themes (src, usersID, `name`, `hash`) VALUE (:src, :userID, :name, :hash)",
        [
          new DatabaseParam("src", $source, PDO::PARAM_STR),
          new DatabaseParam("userID", $user->ID),
          new DatabaseParam("name", substr($themeFile->name, 0, 24), PDO::PARAM_STR),
          new DatabaseParam("hash", $footprint, PDO::PARAM_STR)
        ]
      ));
    }
  ]);
  
  
  
  $themeRouter->get("/user/:offset", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      $response->json(
        Theme::getSet(
          $request->session->get("user")->ID,
          intval($request->param->get("offset"))
        )
      );
    }
  ], ["offset" => Router::REGEX_NUMBER]);