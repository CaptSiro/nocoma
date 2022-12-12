<?php

  require_once __DIR__ . "/../../dotenv/dotenv.php";
  require_once __DIR__ . "/../../lumber/lumber.php";
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
    public static function get (): Database {
      if (!isset($instance)) {
        self::$instance = new Database();
      }

      return self::$instance;
    }

    public function __construct() {
      $env = new Env(ENV_FILE);

      $dbLogin = Result::all($env->get("HOST"), $env->get("PORT"), $env->get("DB_N"), $env->get("USER"), $env->get("PASS"));
      if ($dbLogin->isFailure()) {
        $msg = "";
        foreach ($dbLogin->getFailures() as $exc) {
          $msg .= $exc->getMessage() . "; ";
          Console::exc($exc, "db-log.txt", __DIR__);
        }

        throw new Exception("Invalid login. $msg");
      }
      $login = $dbLogin->getSuccess();

      $connectionString = "mysql:host=$login[0];port=$login[1];dbname=$login[2];charset=UTF8";
      $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // errors from MySQL will appear as PHP Exceptions
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => false // SQL injection
      ];
      $this->con = new PDO($connectionString, $login[3], $login[4], $opt);
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
  
    /**
     * @template T
     * @param string $sql
     * @param T $className
     * @param DatabaseParam[] $params
     * @return T|false
     */
    public function fetch(string $sql, $className, array $params = []) {
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