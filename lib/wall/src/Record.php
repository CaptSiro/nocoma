<?php

  require_once __DIR__ . "/JSONEncodeAble.php";
  require_once __DIR__ . "/OrderedList.php";
  require_once __DIR__ . "/Watcher.php";
  require_once __DIR__ . "/xToArray.php";
  require_once __DIR__ . "/../../retval/retval.php";



  class Record extends JSONEncodeAble {
    public $properties = [], $imports, $files;
    // properties: className, cfn, dir, icon

    public function __construct (array $properties, OrderedList $imports, array $files) {
      $this->properties = $properties;
      $this->imports = $imports;
      $this->files = $files;
    }


    function isValid (): Result {
      $mandatory = ["class", "cfn", "dir"];
      foreach ($mandatory as $prop) {
        if (!isset($this->properties[$prop])) {
          return fail(new NullPointerExc("'$prop' is not defined."));
        }
      }

      return (count($this->files) > 0) ? success(true) : fail(new NullPointerExc("Component must have at least one source file."));
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

    static function parse ($plainObject) {
      $files = [];
      foreach ($plainObject->files as $label => $watcher) {
        $files[$label] = new Watcher($watcher->filePath, $watcher->mtime);
      }

      return new Record(
        xToArray($plainObject->properties),
        OrderedList::parse($plainObject->imports),
        $files
      );
    }
  }