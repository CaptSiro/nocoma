<?php
  
  class RequestFile {
    public $name, $ext, $fullName, $type, $temporaryName, $error, $size;
    
    public function __construct ($file) {
      $this->fullName = $file["name"];
      [$name, $ext] = $this->getExt();
      $this->name = $name;
      $this->ext = $ext;
      $this->type = $file["type"];
      $this->temporaryName = $file["tmp_name"];
      $this->error = $file["error"];
      $this->size = $file["size"];
    }
    
    public function moveTo (string $destination) {
      move_uploaded_file($this->temporaryName, $destination);
    }
    
    public function getExt (): array {
      $ext = "";
      $name = "";
      $switch = false;
      
      for ($i = (strlen($this->fullName) - 1); $i >= 0; $i--) {
        $var = &${$switch ? "name" : "ext"};
        $var = $this->fullName[$i] . $var;
        
        if ($this->fullName[$i] == "." && !$switch) {
          $switch = true;
        }
      }
  
      if ($name === "") {
        return [$ext, $name];
      }
  
      return [$name, $ext];
    }
  }