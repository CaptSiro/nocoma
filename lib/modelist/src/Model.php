<?php 

  require_once __DIR__ . "/../../jsonEncodeAble/jsonEncodeAble.php";
  require_once(__DIR__ . "/Database.php");


  class Model extends JSONEncodeAble {
    public static function generateSelectColumns (string $table, array $columns, bool $isNotLast = false): string {
      $string = "";
      
      $length = count($columns);
      $index = 0;
      foreach ($columns as $name) {
        $string .= "$table.`$name` \"$name\"" . ($length - 1 != $index++ || $isNotLast ? "," : "") . "\n";
      }
      
      return $string;
    }
  
    /**
     * @throws Exception
     */
    public function propExists ($propName): bool {
      $objectProps = get_object_vars($this);

      if (!array_key_exists($propName, $objectProps)) {
        throw new Exception("Property '$propName' does not exist for " . get_class($this) . ":[" . join(", ", array_keys($objectProps)) . "].");
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
  
    /**
     * @throws Exception
     */
    public function __get($propName) {
      if ($this->propExists($propName)) {
        return $this->$propName;
      }
      
      return null;
    }
    
    public function __set($propName, $value) {
      if ($this->propExists($propName)) {
        return $this->$propName = $value;
      }
      
      return null;
    }
  }