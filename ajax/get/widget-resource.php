<?php

  require_once __DIR__ . "/../../lib/rekves/rekves.php";
  require_once __DIR__ . "/../../lib/structure/structure.php";
  require_once __DIR__ . "/../../lib/dotenv/dotenv.php";
  require_once __DIR__ . "/../../lib/paths.php";

  $classes = explode(",", $req->body->c);

  $env = new Env(ENV_FILE);
  $propsRes = $env->get("WIDGETS_DIR");
  $propsRes->forwardFailure($res);

  $parser = new Parser($_SERVER["DOCUMENT_ROOT"] . $propsRes->getSuccess(), Parser::ON_FAIL());

  
  $prepared = [];
  if ($classes[0] == "*") {
    foreach ($parser->registry as $key => $value) {
      $prepared[] = $value->stripPrivate();
    }

    $res->json($prepared);
  }

  foreach ($classes as $c) {
    if (isset($parser->registry[$c])) {
      $prepared[] = $parser->registry[$c]->stripPrivate();
    }
  }

  $res->json($prepared);