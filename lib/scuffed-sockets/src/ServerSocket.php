<?php
  
  require_once __DIR__ . "/Server.php";
  require_once __DIR__ . "/Packet.php";
  
  
  class ServerSocket {
    private $updated;
    private $eventsFile;
    private $id;
    
    public function __construct (int $lastMTime, string $id) {
      $this->updated = $lastMTime === filemtime(__DIR__ . "/events/$id");
      $this->eventsFile = __DIR__ . "/events/$id";
      $this->id = $id;
    }
  
    /**
     * @return Packet[]
     */
    public function getData (): array {
      if ($this->updated === false) return [];
      
      $packets = file_get_contents($this->eventsFile);
      
      if ($packets === "") return [];
      
      file_put_contents($this->eventsFile, "");
      
      return json_decode($packets);
    }
    
    public function postData (Packet $packet) {
      $contents = file_get_contents($this->eventsFile);
      
      if ($contents === "") {
        file_put_contents($this->eventsFile, '[' . json_encode($packet) . ']');
        return;
      }
      
      $packets = json_decode($contents);
      $packets[] = $packet;
      file_put_contents($this->eventsFile, json_encode($packets));
      
      Server::updateSocket($this->id, filemtime($this->eventsFile));
    }
  }