<?php
  
  require_once __DIR__ . "/ServerSocket.php";
  require_once __DIR__ . "/Packet.php";
  
  const SOCKET_FILE = __DIR__ . "/sockets.ini";
  const SOCKET_EVENTS = __DIR__ . "/events";

  class Server {
    private static function saveSockets (array $sockets) {
      $file = fopen(SOCKET_FILE, "w");
      foreach ($sockets as $id => $mtime) {
        fwrite($file, "$id=$mtime\n");
      }
      fclose($file);
    }
  
    /**
     * @param string $socketID must follow file name guidelines
     * @return Result
     */
    public static function accept (string $socketID): Result {
      $sockets = parse_ini_file(SOCKET_FILE);
      
      if (!file_exists(__DIR__ . "/events/$socketID")) {
        if (!touch(__DIR__ . "/events/$socketID")) {
          return fail(new InvalidArgumentExc("Could not create a file to socketID"));
        }
      } else {
        file_put_contents(__DIR__ . "/events/$socketID", "");
      }
      
      $mtime = filemtime(SOCKET_EVENTS . "/$socketID");
      
      $sockets[$socketID] = $mtime;
      self::saveSockets($sockets);
      
      return success(new ServerSocket($mtime, $socketID));
    }
    
    public static function destroy (string $socketID) {
      unlink(SOCKET_EVENTS . "/$socketID");
      
      $sockets = parse_ini_file(SOCKET_FILE);
      unset($sockets[$socketID]);
      self::saveSockets($sockets);
    }
    
    public static function getSocket (string $socketID): Result {
      $sockets = parse_ini_file(SOCKET_FILE);
      
      if (!isset($sockets[$socketID])) {
        return fail(new NotFoundExc("Could not find socket with id: $socketID"));
      }
      
      return success(new ServerSocket($sockets[$socketID], $socketID));
    }
    
    public static function broadcast (Packet $packet, array $except = []) {
      $sockets = parse_ini_file(SOCKET_FILE);
      foreach ($sockets as $id => $time) {
        if (in_array($id, $except)) continue;
        
        $socket = new ServerSocket($time, $id);
        $socket->postData($packet);
      }
    }
    
    public static function updateSocket (string $socketID, int $mtime) {
      $sockets = parse_ini_file(SOCKET_FILE);
      $sockets[$socketID] = $mtime;
      self::saveSockets($sockets);
    }
  }