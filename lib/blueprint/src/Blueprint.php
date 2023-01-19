<?php
  
  class Blueprint {
    /** @var Closure[] $tests */
    protected $tests = [];
    protected $options;
    protected $isStrict = true;
    
    const REQUIRED_MESSAGE = "required_message";
    const INVALID_TYPE_MESSAGE = "invalid_type_message";
    
    protected function pass (Closure $test) {
      $this->tests[] = $test;
    }
    
    public function __construct (array $options = []) {
      $this->options = $options;
    }
  
  
    public function parse ($value): array {
      if ($this->isStrict && $value === null) {
        return [false, $this->options[self::REQUIRED_MESSAGE] ?? "Found null."];
      }
      
      foreach ($this->tests as $test) {
        $returned = $test($value);
        
        if ($returned !== "") {
          return [false, $returned];
        }
      }
      
      return [true, $value];
    }
    
    
    
    public function optional (): Blueprint {
      $this->isStrict = false;
      
      return $this;
    }
    
    public function typeOf (string $type): Blueprint {
      $this->pass(function ($value) use ($type) {
        if (gettype($value) !== $type) {
          return $this->options[self::INVALID_TYPE_MESSAGE] ?? "Expected type of $type got " . gettype($value);
        }
        
        return "";
      });
  
      return $this;
    }
    
    public function equals ($compareTo): Blueprint {
      $this->pass(function (&$value) use ($compareTo) {
        if ($value != $compareTo) {
          return "Value does not match " . json_encode($compareTo);
        }
        
        return "";
      });
  
      return $this;
    }
  
    public function equalsStrict ($compareTo): Blueprint {
      $this->pass(function (&$value) use ($compareTo) {
        if ($value !== $compareTo) {
          return "Value and type does not match " . json_encode($compareTo) . " and " . gettype($compareTo);
        }
      
        return "";
      });
  
      return $this;
    }
    
    public function convert ($convertor): Blueprint {
      $this->pass($convertor);
  
      return $this;
    }
  }