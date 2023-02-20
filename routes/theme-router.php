<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/newgen/newgen.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/Theme.php";
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../models/Website.php";
  require_once __DIR__ . "/../models/Media.php";
  
  $themeRouter = new Router();
  
  
  
  $themeRouter->options("/:src", [Middleware::corsAllowAll("GET")]);
  $themeRouter->get("/:src", [function (Request $request, Response $response) {
    $src = $request->param->get("src");
    if ($src[0] === "_" && strlen($src) === 9) {
      $path = GLOBAL_THEMES_DIR . "/" . substr($src, 1) . ".css";
      if (!file_exists($path)) {
        $response->error("Invalid global theme source.", Response::IM_A_TEAPOT);
      }
      
      $response->setHeader("Content-Type", "text/css");
      $response->readFile($path);
    }
    
    $themeResult = Theme::getBySRC($src);
    if ($themeResult->isFailure()) {
      $defaults = Theme::getDefaults();
      if (!$defaults) {
        $response->fail(new Exc("No default themes."));
      }
  
      $defaultPath = GLOBAL_THEMES_DIR . "/" . substr($defaults[0]->src, 1) . ".css";
      $response->setHeader("Content-Type", "text/css");
      $response->readFile($defaultPath);
    }
    $theme = $themeResult->getSuccess();
    
    
    $themeFilePath = HOSTS_DIR . "/$theme->website/media/$src.css";
    $response->setHeader("Content-Type", "text/css");
    $response->readFile($themeFilePath);
  }], [
    "src" => Router::REGEX_BASE64_URL_SAFE
  ]);
  
  
  $themeRouter->get("/defaults", [
    function (Request $request, Response $response) {
      $response->json(Theme::getDefaults());
    }
  ]);
  
  
  
  
  
  
  
  
  
  
  function serveTheme ($themeSRC, $website, $response) {
    if ($themeSRC === null && $website !== "") {
      $themeSRC = User::getByWebsite($website)
        ->forwardFailure($response)
        ->getSuccess()->themesSRC;
    }
    
    if ($themeSRC === null) {
      $defaults = Theme::getDefaults();
      if (!$defaults) {
        $response->fail(new Exc("No default themes."));
      }
  
      $defaultPath = GLOBAL_THEMES_DIR . "/" . substr($defaults[0]->src, 1) . ".css";
      if (!file_exists($defaultPath)) {
        $response->fail(new NotFoundExc("Could not find theme $defaultPath."));
      }
  
      $response->json([
        "src" => $defaults[0]->src,
        "styles" => file_get_contents($defaultPath)
      ]);
    }
  
    if ($themeSRC[0] === "_" && strlen($themeSRC) === 9) {
      $path = GLOBAL_THEMES_DIR . "/" . substr($themeSRC, 1) . ".css";
      if (!file_exists($path)) {
        $response->fail(new NotFoundExc("Invalid global theme source."));
      }
  
      $response->json([
        "src" => $themeSRC,
        "styles" => file_get_contents($path)
      ]);
    }
  
    $customThemePath = HOSTS_DIR . "/$website/media/$themeSRC.css";
    if (!file_exists($customThemePath)) {
      $response->fail(new NotFoundExc("Invalid theme source."));
    }
  
    $response->json([
      "src" => $themeSRC,
      "styles" => file_get_contents($customThemePath)
    ]);
  }
  
  
  
  function getThemeContents ($themeSRC, $website): Result {
    if ($themeSRC === null) {
      $defaults = Theme::getDefaults();
      if (!$defaults) {
        return fail(new Exc("No default themes."));
      }
    
      $defaultPath = GLOBAL_THEMES_DIR . "/" . substr($defaults[0]->src, 1) . ".css";
      if (!file_exists($defaultPath)) {
        return fail(new NotFoundExc("Could not find theme $defaultPath."));
      }
    
      return success([
        "src" => $defaults[0]->src,
        "styles" => file_get_contents($defaultPath)
      ]);
    }
  
    if ($themeSRC[0] === "_" && strlen($themeSRC) === 9) {
      $path = GLOBAL_THEMES_DIR . "/" . substr($themeSRC, 1) . ".css";
      if (!file_exists($path)) {
        return fail(new NotFoundExc("Invalid global theme source."));
      }
    
      return success([
        "src" => $themeSRC,
        "styles" => file_get_contents($path)
      ]);
    }
  
    $customThemePath = HOSTS_DIR . "/$website/media/$themeSRC.css";
    if (!file_exists($customThemePath)) {
      return fail(new NotFoundExc("Invalid theme source."));
    }
  
    return success([
      "src" => $themeSRC,
      "styles" => file_get_contents($customThemePath)
    ]);
  }
  
  
  $themeRouter->options("/website/:src", [
    Middleware::corsAllowAll("GET")
  ], ["src" => Router::REGEX_BASE64_URL_SAFE]);
  $themeRouter->get("/website/:src", [function (Request $request, Response $response) {
    $response->setHeader(Response::HEADER_CORS_ORIGIN, "*");
    
    $website = Website::getBySourceWithUser($request->param->get("src"))
      ->forwardFailure($response)
      ->getSuccess();
    
    serveTheme($website->themesSRC ?? null, $website->website ?? "", $response);
  }], ["src" => Router::REGEX_BASE64_URL_SAFE]);
  
  
  
  
  
  
  
  $themeRouter->delete("/:src", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      $response->json(Theme::delete(
        $request->param->get("src"),
        $request->session->get("user")->website
      ));
    }
  ], ["src" => Router::REGEX_BASE64_URL_SAFE]);
  
  
  
  
  
  $themeRouter->patch("/rename/:src", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      $response->json(Theme::rename(
        $request->param->get("src"),
        $request->body->get("name")
      ));
    }
  ], ["src" => Router::REGEX_BASE64_URL_SAFE]);
  
  
  
  
  
  
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
        Generate::string(Generate::CHARSET_URL, Media::SRC_LENGTH_LASTING),
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
  
  
  
  
  /**
   * imageSRC
   * name
   * a = 3
   * b = 2
   * rounding = 20
   */
  $themeRouter->post("/generate", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      require_once __DIR__ . "/../models/DynamicTheme.php";
      
      Image::$colorChannelRounding = intval($request->body->looselyGet("rounding", 20));
      $response->json(
        ["src" => DynamicTheme::createFrom(
          $request->body->get("name"),
          $request->body->get("imageSRC"),
          $request->session->get("user")->website,
          $request->body->looselyGet("a", 3),
          $request->body->looselyGet("b", 2),
        )
          ->forwardFailure($response)
          ->getSuccess()]
      );
    }
  ]);
  
  
  
  
  
  
  $themeRouter->get("/user/", [
    function (Request $request, Response $response) {
      /**
       * @var User $user
       */
      $user = $request->session->looselyGet("user", new stdClass());
      serveTheme($user->themesSRC ?? null, $user->website ?? "", $response);
    }
  ]);
  
  
  
  $themeRouter->get("/user/all", [
    function (Request $request, Response $response) {
      if (!$request->session->isset("user")) {
        $defaults = Theme::getDefaults();
        
        if (!$defaults) {
          $response->fail(new Exc("No default themes."));
        }
        
        $response->json($defaults);
      }
    
      $response->json(
        Theme::getAllUsers($request->session->get("user")->ID));
    }
  ]);
  
  
  $themeRouter->get("/user/all-v2", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      $themes = Theme::getAllUsers($request->session->get("user")->ID);
      if (!$themes) {
        $response->fail(new Exc("Could not find any themes."));
      }
    
      $website = $request->session->get("user")->website;
      $response->json(array_map(function ($theme) use ($website, $response) {
        $contents = getThemeContents($theme->src, $website);
        if ($contents->isFailure()) return null;
        
        $success = $contents->getSuccess();
        return [
          "src" => $success["src"],
          "usersID" => $theme->usersID,
          "name" => $theme->name,
          "styles" => $success["styles"]
        ];
      }, $themes));
    }
  ]);
  
  
  
  return $themeRouter;