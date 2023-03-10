<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/newgen/newgen.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../models/Theme.php";
  require_once __DIR__ . "/../models/Media.php";
  require_once __DIR__ . "/../models/Website.php";
  
  $fileRouter = new Router();
  
  
  
  
  $setTypes = ["", "image"];
  $fileRouter->get("/:offset", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) use ($setTypes) {
      $type = $request->query->looselyGet("type");
      if ($type === null || in_array($type, $setTypes)) {
        $response->json(Media::getSet(
          $request->session->get("user")->ID,
          intval($request->param->get("offset")),
          $request->query->looselyGet("order", "0"),
          $type ?: ""
        ));
      }
      
      if ($type === "theme") {
        $response->json(Theme::getSet(
          $request->session->get("user")->ID,
          intval($request->param->get("offset"))
        ));
      }
    }
  ], ["offset" => Router::REGEX_NUMBER]);
  
  
  
  
  $fileRouter->options("/:websiteSRC/:file", [Middleware::corsAllowAll("GET")], [
    "websiteSRC" => Router::REGEX_BASE64_URL_SAFE,
    "file" => Router::REGEX_ANY,
  ]);
  $fileRouter->get("/:websiteSRC/:file", [function (Request $request, Response $response) {
    $response->setCORS();
    
    $website = Website::getBySourceWithUser($request->param->get("websiteSRC"))
      ->forwardFailure($response)
      ->getSuccess()->website;
    
    $filePath = HOSTS_DIR . "/$website/media/" . $request->param->get("file");
    
    $type = Response::getMimeType($filePath)
      ->forwardFailure($response)
      ->getSuccess();
    
    $response->sendOptimalImage(
      $filePath,
      $type,
      $request
    );

    $response->setHeader("Content-Type", $type);
    $response->readFile($filePath);
  }], [
    "websiteSRC" => Router::REGEX_BASE64_URL_SAFE,
    "file" => Router::REGEX_ANY,
  ]);
  
  
  
  
  $fileRouter->get("/info/:website/:file", [function (Request $request, Response $response) {
    if (glob(HOSTS_DIR . "/" . $request->param->get("website") . "/media/" . $request->param->get("file") . ".*") === false) {
      $response->fail(new NotFoundExc("Could not find your file."));
    }
    
    $response->json(Media::getBySource($request->param->get("file")));
  }], [
    "website" => Router::REGEX_BASE64_URL_SAFE,
    "file" => Router::REGEX_BASE64_URL_SAFE,
  ]);
  
  
  
  
  
  $fileRouter->get("/collect", [
    Middleware::requireToBeLoggedIn(Middleware::RESPONSE_REDIRECT),
    function (Request $request, Response $response) {
      $response->render("collect");
    }
  ]);
  
  
  
  
  
  $fileRouter->get("/download", [function (Request $request, Response $response) {
    $website = $request->query->get("website");
    $name = $request->query->looselyGet("name");
    if ($name === "") {
      $name = null;
    }
    $files = explode(",", $request->query->get("files"));
    
    if (count($files) === 0) {
      // todo make into file-download-error view
      $response->render("error", ["message" => "No files to be downloaded."]);
    }
    
    $userDirectory = HOSTS_DIR . "/$website/media";
    
    if (count($files) === 1) {
      /**
       * @var Media $file
       */
      $file = Media::getBySource($files[0])
        ->forwardFailure($response)
        ->getSuccess();
      $filePath = $userDirectory . "/$file->src$file->extension";
      
      if (!file_exists($filePath)) {
        $response->fail(new NotFoundExc("File not found"));
      }
      
      $response->download($filePath, ($name ?? $file->basename) . $file->extension);
      $response->flush();
    }
  
    $zipUniqueSRC = Generate::valid(
      Generate::string(Generate::CHARSET_URL, Media::SRC_LENGTH_TEMPORARY),
      function ($src) use ($userDirectory) {
        if (!file_exists("$userDirectory/$src.zip")) return success(false);
        return success(true);
      }
    )
      ->forwardFailure($response)
      ->getSuccess();
    
    $zipFileName = "$userDirectory/$zipUniqueSRC.zip";
    
    $zip = new ZipArchive();
    if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
      $response->fail(new Exc("Could not create zip archive"));
    }
    
    foreach ($files as $fileSRC) {
      $file = Media::getBySource($fileSRC)
        ->forwardFailure($response)
        ->getSuccess();
      
      if (!file_exists("$userDirectory/$fileSRC$file->extension")) continue;
      
      $zip->addFile("$userDirectory/$fileSRC$file->extension", $file->name());
    }
    
    if ($zip->count() === 0) {
      $zip->close();
      unlink($zipFileName);
      
      $response->fail(new Exc("No valid files found."));
    }
    
    $zip->close();
    
    $response->download($zipFileName, ($name ?? "archive_" . date("Y-m-d"))  . ".zip");
    @unlink($zipFileName);
    $response->flush();
  }]);
  
  
  
  $fileRouter->options("/size", [Middleware::corsAllowAll("GET")]);
  $fileRouter->get("/size", [function (Request $request, Response $response) {
    $response->setHeader(Response::HEADER_CORS_ORIGIN, "*");
    
    $files = explode(",", $request->query->get("files"));
    
    $website = $request->query->get("website");
    
    $totalSize = 0;
    foreach ($files as $fileSRC) {
      if ($fileSRC === "") continue;
  
      $file = Media::getBySource($fileSRC)
        ->forwardFailure($response)
        ->getSuccess();
  
      $filePath = HOSTS_DIR . "/$website/media/$fileSRC$file->extension";
      if (!file_exists($filePath)) continue;
  
      $totalSize += filesize($filePath);
    }
    
    $response->send($totalSize);
  }]);
  
  
  
  $fileRouter->post("/collect", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      if (!is_array($request->files->get("uploaded"))) {
        $response->json(["error" => "Uploaded files must be in array."]);
      }
      
      foreach ($request->files->get("uploaded") as $file) {
        Media::save($file, $request->session->get("user"))
          ->forwardFailure($response);
      }
      
      $response->json(["message" => "ok"]);
    }
  ]);
  
  
  
  
  
  $fileRouter->delete("/:file", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      $response->json(
        Media::delete($request->param->get("file"), $request->session->get("user"))
          ->forwardFailure($response)
          ->getSuccess()
      );
    }
  ], ["file" => Router::REGEX_BASE64_URL_SAFE]);
  
  
  
  
  
  $fileRouter->patch("/:file", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      $response->json(Media::rename($request->param->get("file"), $request->body->get("value")));
    }
  ], ["file" => Router::REGEX_BASE64_URL_SAFE]);
  
  
  
  
  
  return $fileRouter;