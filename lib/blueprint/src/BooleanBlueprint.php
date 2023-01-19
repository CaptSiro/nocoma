<?php
  
  require_once __DIR__ . "/Blueprint.php";
  
  class BooleanBlueprint extends Blueprint {
    public function __construct (array $options = []) {
      parent::__construct($options);
      $this->typeOf("boolean");
    }
    
    
    
    public function truthy (): BooleanBlueprint {
      $this->equalsStrict(true);
      return $this;
    }
    
    public function falsy (): BooleanBlueprint {
      $this->equalsStrict(false);
      return $this;
    }
    
    public static function convertor () {
      return function (&$value) {
        $value = boolval($value);
        return "";
      };
    }
  }