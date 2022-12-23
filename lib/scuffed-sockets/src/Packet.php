<?php
  
  require_once __DIR__ . "/../../jsonEncodeAble/jsonEncodeAble.php";
  
  class Packet extends JSONEncodeAble {
    public $event;
    public $data;
    
    public function __construct (string $data, string $event = "ping") {
      $this->event = $event;
      $this->data = $data;
    }
    
    public function httpfy () {
      return "event: $this->event\ndata: $this->data\n\n";
    }
    
    public function jsonData () {
      return json_decode($this->data);
    }
  }