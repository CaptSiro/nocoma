<?php

  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/structure/structure.php";
  require_once __DIR__ . "/../lib/lumber/lumber.php";
  require_once __DIR__ . "/../lib/dotenv/dotenv.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  $env = new Env(ENV_FILE);
  $bundlerRouter = new Router();
  
  $processRecord = function (Record $record, Parser $parser, Response $response, &$records = []) {
    if (!$record->isUpToDate()) {
      $updateResult = $parser->updateRecord($record);
      
      if ($updateResult->isFailure()) {
        Console::exc($updateResult->getFailure(), "bundle-exceptions.txt", __DIR__);
        $response->error($updateResult->getFailure()->getMessage(), Response::INTERNAL_SERVER_ERROR);
      }
    }
    
    $records[] = $record;
  };
  
  $recordsPreprocessor = function (Request $request, Response $response, Closure $next) use ($env, $processRecord) {
    $widgetsDirectoryResult = $env->get("WIDGETS_DIR");
    $widgetsDirectoryResult->forwardFailure($response);
    
    $parser = new Parser($_SERVER["DOCUMENT_ROOT"] . $widgetsDirectoryResult->getSuccess(), Parser::ON_FAIL());
    $importOrder = $parser->getClassSet(explode(",", $request->param->get("widgets")));
    
    $records = [];
    foreach ($importOrder as $class) {
      $processRecord($parser->registry[$class], $parser, $response, $records);
    }
    
    $next($records);
  };
  
  
  
  
  $bundlerRouter->get("/js/:widgets", [
    $recordsPreprocessor,
    function (Request $request, Response $response, Closure $next, array $records) use ($env) {
      $response->setHeader("Content-Type", "text/javascript");
    
      /** @var Record $record */
      foreach ($records as $record) {
        $response->readFile($record->properties["cfn"], false);
        echo "\n";
      }
      
      $response->end();
    }
  ]);
  
  
  
  
  
  $bundlerRouter->get("/css/:widgets", [
    $recordsPreprocessor,
    function (Request $request, Response $response, Closure $next, array $records) use ($env) {
      $response->setHeader("Content-Type", "text/css");
  
      /** @var Record $record */
      foreach ($records as $record) {
        $response->readFile($record->files["styles"]->filePath, false);
        echo "\n";
      }
  
      $response->end();
    }
  ]);
  
  
  
  
  
  $bundlerRouter->get("/file/:widget", [function (Request $request, Response $response) use ($env, $processRecord) {
    $widgetsDirectoryResult = $env->get("WIDGETS_DIR");
    $widgetsDirectoryResult->forwardFailure($response);
  
    $parser = new Parser($_SERVER["DOCUMENT_ROOT"] . $widgetsDirectoryResult->getSuccess(), Parser::ON_FAIL());
    $widget = $request->param->get("widget");
    $file = $request->query->looselyGet("f", "icon");
    
    if (!isset($parser->registry[$widget])) {
      $response->error("Could not find widget by class '" . $widget . "'.", Response::NOT_FOUND);
    }
  
    $stripped = $parser->registry[$widget]->stripPrivate(false);
    if (!isset($stripped->files[$file])) {
      $response->error("Could not find file '$file' in files array.", Response::NOT_FOUND);
    }
  
    $fileName = $stripped->files[$file];
    $mimeTypeResult = Response::getMimeType($fileName);
    $mimeTypeResult->forwardFailure($response);
    
    $response->setHeader("Content-Type", $mimeTypeResult->getSuccess());
    $response->readFile($fileName);
  }]);
  
  
  
  
  
  $bundlerRouter->get("/resource/:widgets", [function (Request $request, Response $response) use ($env, $processRecord) {
    $widgetsDirectoryResult = $env->get("WIDGETS_DIR");
    $widgetsDirectoryResult->forwardFailure($response);
  
    $parser = new Parser($_SERVER["DOCUMENT_ROOT"] . $widgetsDirectoryResult->getSuccess(), Parser::ON_FAIL());
    $widgets = explode(",", $request->param->get("widgets"));
  
    $prepared = [];
    if ($widgets[0] == "*") {
      foreach ($parser->registry as $record) {
        $prepared[] = $record->stripPrivate();
      }
    
      $response->json($prepared);
    }
  
    foreach ($widgets as $widget) {
      if (isset($parser->registry[$widget])) {
        $prepared[] = $parser->registry[$widget]->stripPrivate();
      }
    }
  
    $response->json($prepared);
  }]);
  
  
  
  
  
  return $bundlerRouter;