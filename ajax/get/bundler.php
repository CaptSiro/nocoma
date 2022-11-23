<?php

  require_once __DIR__ . "/../../lib/rekves/rekves.php";
  require_once __DIR__ . "/../../lib/structure/structure.php";
  require_once __DIR__ . "/../../lib/lumber/Console.php";
  require_once __DIR__ . "/../../lib/dotenv/dotenv.php";
  require_once __DIR__ . "/../../lib/paths.php";

  $widgetsDirRes = (new Env(ENV_FILE))->get("WIDGETS_DIR");
  $widgetsDirRes->forwardFailure($res);

  $parser = new Parser($_SERVER["DOCUMENT_ROOT"] . $widgetsDirRes->getSuccess());
  $importOrder = $parser->getClassSet(explode(",", $req->body->w));


  foreach ($importOrder as $class) {
    $r = $parser->registry[$class];

    if (!$r->isUpToDate()) {
      $updateRes = $parser->updateRecord($r);
      if ($updateRes->isFailure()) {
        Console::print($updateRes->getFailure(), "bundler-exc.txt", __DIR__);
        $res->error($updateRes->getFailure()->getMessage(), Response::INTERNAL_SERVER_ERROR);
      }
    }

    if ($req->body->get("ftype", "js") == "js") {
      readfile($r->properties["cfn"]);
    } else {
      readfile($r->files["styles"]->filePath);
    }
    echo "\n";
  }


  if ($req->body->get("ftype", "js") == "css") {
    $res->setHeader("Content-type", "text/css");
  }
  $res->generateHeaders();

?>