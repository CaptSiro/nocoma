<?php

  require_once __DIR__ . "/../../lib/rekves/rekves.php";
  require_once __DIR__ . "/../../lib/structure/structure.php";
  require_once __DIR__ . "/../../lib/dotenv/dotenv.php";
  require_once __DIR__ . "/../../lib/paths.php";

  $env = new Env(ENV_FILE);
  $widgetsDirRes = $env->get("WIDGETS_DIR");
  $widgetsDirRes->forwardFailure($res);

  $parser = new Parser($_SERVER["DOCUMENT_ROOT"] . $widgetsDirRes->getSuccess(), Parser::ON_FAIL());

  if (!isset($parser->registry[$req->body->c])) {
    $res->error("Could not find widget by class '" . $req->body->c . "'.", Response::NOT_FOUND);
  }

  $stripped = $parser->registry[$req->body->c]->stripPrivate(false);
  if (!isset($stripped->files[$req->body->get("f", "icon")])) {
    $res->error("Could not find file '" . $req->body->get("f", "icon") . "' in files array.", Response::NOT_FOUND);
  }

  $fileName = $stripped->files[$req->body->get("f", "icon")];
  $res->setHeader("Content-type", mime_content_type($fileName));
  readfile($fileName);
  $res->flush();