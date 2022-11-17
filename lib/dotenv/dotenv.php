<?php

  require_once __DIR__ . "/../retval/retval.php";

  class Env {
    private $__map = [];
    function __construct ($file) {
      $this->__map["__ENV_FILE__"] = $file;

      $handle = fopen($file, "r");
      if ($handle) {
        while (($line = fgets($handle)) !== false) {
          preg_match("/(.*)=(.*).?#?.*/", rtrim($line, "\r\n"), $matches);
          $this->__map[$matches[1]] = $matches[2];
        }

        fclose($handle);
      }
    }

    public function get ($name): Result {
      if (!isset($this->__map[$name])) {
        return fail(new NotFoundExc("Could not find $name in " . $this->__map["__ENV_FILE__"]));
      }
  
      return success($this->__map[$name]);
    }
    
    function __get ($name) {
      if (!isset($this->__map[$name])) {
        return null;
      }
  
      return $this->__map[$name];
    }
  }