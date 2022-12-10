<?php

  require_once __DIR__ . "/../../jsonEncodeAble/jsonEncodeAble.php";
  require_once __DIR__ . "/Watcher.php";
  require_once __DIR__ . "/xToArray.php";

  class Record extends JSONEncodeAble {
    public $properties = [], $imports, $files;
    // properties: class, dir, label, category

    public function __construct (array $properties, array $imports, array $files) {
      $this->properties = $properties;
      $this->imports = $imports;
      $this->files = $files;
    }


    function isValid (): Result {
      $mandatory = ["class", "dir"];
      foreach ($mandatory as $prop) {
        if (!isset($this->properties[$prop])) {
          return fail(new NullPointerExc("'$prop' is not defined."));
        }
      }

      return (count($this->files) > 0) ? success(true) : fail(new NullPointerExc("Widget must have at least one source file."));
    }




    public function addFile (string $label, string $fileName) {
      $this->files[] = new Watcher($label, $fileName, time());
    }

    public function isUpToDate (): bool {
      foreach ($this->files as $watcher) {
        if ($watcher->isOutdated()) {
          return false;
        }
      }

      return true;
    }

    public function update () {
      foreach ($this->files as $watcher) {
        $watcher->update();
      }
    }

    public function stripPrivate ($doStripFilePaths = true): Record {
      $r = new Record($this->properties, $this->imports, $this->files);
      unset($r->properties["cfn"]);
      unset($r->properties["dir"]);
      unset($r->files["source"]);
      unset($r->files["config"]);

      $f = [];
      foreach ($r->files as $key => $file) {
        $f[$key] = $doStripFilePaths ? (str_replace($_SERVER["DOCUMENT_ROOT"], "", $file->filePath)) : $file->filePath;
      }

      $r->files = $f;

      return $r;
    }






    static function parse ($plainObject) {
      $files = [];
      foreach ($plainObject->files as $label => $watcher) {
        $files[$label] = new Watcher($watcher->filePath, $watcher->mtime);
      }

      return new Record(
        xToArray($plainObject->properties),
        $plainObject->imports,
        $files
      );
    }
  }