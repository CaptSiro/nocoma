<?php

  require_once __DIR__ . "/../../dotenv/dotenv.php";
  require_once __DIR__ . "/../../paths.php";
  require_once(__DIR__ . "/DatabaseParam.php");

  class SideEffect {
    public $lastInsertedID, $rowCount;

    public function __construct ($liID, $rc) {
      $this->lastInsertedID = $liID;
      $this->rowCount = $rc;
    }
  }

  class Database {
    private $con;
    private static $instance;
    public static function get () {
      if (!isset($instance)) {
        $instance = new Database();
      }

      return $instance;
    }

    public function __construct() {
      $env = new Env(ENV_FILE);
      $connectionString = "mysql:host=$env->HOST;port=$env->PORT;dbname=$env->DB_N;charset=UTF8";
      $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // errors from MySQL will appear as PHP Exceptions
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => false // SQL injection
      ];
      $this->con = new PDO($connectionString, $env->USER, $env->PASS, $opt);
    }

    public function lastInsertId() {
      return $this->con->lastInsertId();
    }

    public function highestID ($table) {
      return $this->fetch(
        "SELECT MAX(ID) \"max\" FROM $table",
        "stdClass"
      )->max;
    }

    public function statement($sql, $params = []): SideEffect {
      $stmt = $this->con->prepare($sql);
      foreach($params as $param) {
        $stmt->bindValue($param->name, $param->value, $param->type);
      }
      $stmt->execute();
      return new SideEffect($this->con->lastInsertId(), $stmt->rowCount());
    }

    public function fetch($sql, $className, $params = []) {
      $stmt = $this->con->prepare($sql);
      foreach($params as $param) {
        $stmt->bindValue($param->name, $param->value, $param->type);
      }
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_CLASS, $className);
      return $stmt->fetch();
    }

    public function fetchAll($sql, $className, $params = []) {
      $stmt = $this->con->prepare($sql);
      foreach($params as $param) {
        $stmt->bindValue($param->name, $param->value, $param->type);
      }
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_CLASS, $className);
      return $stmt->fetchAll();
    }
  }