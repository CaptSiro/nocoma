<?php

  require_once __DIR__ . "/../../lib/rekves/rekves.php";
  require_once __DIR__ . "/../../lib/wall/wall.php";

  $packager = new Packager();


  // var_dump(explode(",", $req->body->class));
  $importOrder = $packager->getClassSet(explode(",", $req->body->class));

  foreach ($importOrder as $class) {
    $record = $packager->registry[$class];
    
    if (!$record->isUpToDate()) {
      $compileRes = $packager->register($record->files["xml"]->filePath);
      if ($compileRes->isFailure()) {
        $res->error($compileRes->getFailure()->getMessage(), Response::INTERNAL_SERVER_ERROR);
      }
    }

    readfile($packager->registry[$class]->properties["cfn"]);
  }