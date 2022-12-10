<?php 

  require_once __DIR__ . "/../../jsonEncodeAble/jsonEncodeAble.php";
  require_once(__DIR__ . "/Database.php");


  class Model extends JSONEncodeAble {
    public function propExists ($propName) {
      $objectProps = get_object_vars($this);

      if (!array_key_exists($propName, $objectProps)) {
        throw new Exception("Property '{$propName}' does not exist for " . get_class($this) . ":[" . join(", ", array_keys($objectProps)) . "].");
        return false;
      }

      return true;
    }

    public static function setKeys (&$applyOn, $findKeyHere) {
      $acc = [];

      foreach ($applyOn as $value) {
        $acc[$value->$findKeyHere] = $value;
      }

      return $acc;
    }

    public function __get($propName) {
      if ($this->propExists($propName)) {
        return $this->$propName;
      }
    }
    
    public function __set($propName, $value) {
      if ($this->propExists($propName)) {
        return $this->$propName = $value;
      }
    }
  }