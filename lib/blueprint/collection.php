<?php
  
  require_once __DIR__ . "/src/BooleanBlueprint.php";
  
  class Blueprints {
    public function boolean (array $options = []) {
      return new BooleanBlueprint($options);
    }
  }
  
  return new Blueprints();