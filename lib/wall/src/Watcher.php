<?php

  require_once __DIR__ . "/JSONEncodeAble.php";

  class Watcher extends JSONEncodeAble {
    public $filePath, $mtime;

    public function __construct (string $filePath, int $mtime) {
      $this->filePath = $filePath;
      $this->mtime = $mtime;
    }

    public function update () {
      $this->mtime = filemtime($this->filePath);
    }

    public function isOutdated (): bool {
      return filemtime($this->filePath) !== $this->mtime;
    }

    static function parseJSON (?string $json): Watcher {
      $d = json_decode($json);
      return new Watcher ($d->filePath, intval($d->mtime));
    }

    static function parse ($plainObject): Watcher {
      return new Watcher($plainObject->filePath, $plainObject->mtime);
    }
  }