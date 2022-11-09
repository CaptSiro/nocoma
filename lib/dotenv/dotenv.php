<?php

  class Env {
    private $__map = [];
    function __construct ($file) {
      $handle = fopen($file, "r");
      if ($handle) {
        while (($line = fgets($handle)) !== false) {
          preg_match("/(.*)=(.*)/", rtrim($line, "\r\n"), $matches);
          $this->__map[$matches[1]] = $matches[2];
        }

        fclose($handle);
      }
    }

    function __get ($name) {
      return $this->__map[$name];
    }
  }