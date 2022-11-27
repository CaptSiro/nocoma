<?php

  require_once __DIR__ . "/../../retval/retval.php";
  require_once __DIR__ . "/Record.php";

  const SAVE_FILE = "registry.json";





  function array_flat (array &$multiDimensional): array {
    $arr = [];

    array_walk_recursive($multiDimensional, function ($e) use (&$arr) {
      $arr[] = $e;
    });

    return $arr;
  }


  function match_char_seq (string $str, int $startIndex, $charSeq): bool {
    for ($i = 0; $i < strlen($charSeq); $i++) { 
      if (!(isset($str[$startIndex + $i]) && $str[$startIndex + $i] == $charSeq[$i])) {
        return false;
      }
    }

    return true;
  }






  class Parser {
    public static function ON_FAIL () {
      return function (Exc $failure) {
        global $res;
        if (!isset($res)) {
          $res = new Response();
        }

        $res->error($failure->getMessage(), Response::INTERNAL_SERVER_ERROR);
        return $failure;
      };
    }

    public $registry = [];
    private function addRecord (string $class, Record $r) {
      $this->registry[$class] = $r;
      file_put_contents(__DIR__ . "/" . SAVE_FILE, json_encode($this->registry));
    }



    public function __construct (string $widgetsDirectory, Closure $onFail, $hardReset = false) {
      $glob = glob("$widgetsDirectory/**/widget-config.php");
      $decoded = json_decode(file_get_contents(__DIR__ . "/" . SAVE_FILE));

      if ($decoded == null || count(get_object_vars($decoded)) != count($glob)) {
        foreach ($glob as $config) {
          $recordRes = $this->createRecord($config)->failed($onFail);

          if ($recordRes->isSuccess()) {
            $r = $recordRes->getSuccess();
            $this->registry[$r->properties["class"]] = $r;
          }
        }

        $compiledGlob = glob(__DIR__ . "/compiled-widgets/*");
        foreach ($compiledGlob as $compiled) {
          $class = str_replace(".comp", "", pathinfo($compiled)["filename"]);

          if (!isset($this->registry[$class])) {
            unlink($compiled);
          }
        }
        
        file_put_contents(__DIR__ . "/" . SAVE_FILE, json_encode($this->registry));
        return;
      }
      
      $modified = false;
      foreach ($decoded as $className => $record) {
        $this->registry[$className] = Record::parse($record);

        if (!$this->registry[$className]->isUpToDate()) {
          $recRes = $this->updateRecord($this->registry[$className])->failed($onFail);

          if ($recRes->isSuccess()) {
            $modified = true;
            $this->registry[$className] = $recRes->getSuccess();
          }
        }
      }

      if ($modified == true) {
        file_put_contents(__DIR__ . "/" . SAVE_FILE, json_encode($this->registry));
      }
    }






    public function getSet (string $class): array {
      if (!isset($this->registry[$class])) {
        return [];
      }

      $set = [];
      $record = $this->registry[$class];
      foreach ($record->imports as $c) {
        $set[] = $this->getSet($c);
      }

      $set[] = $class;

      return $set;
    }

    public function getClassSet (array $classes) {
      if ($classes[0] === "*") {
        $classes = array_keys($this->registry);
      }

      $collector = [];

      foreach ($classes as $class) {
        $collector[] = $this->getSet($class);
      }

      return array_unique(array_flat($collector));
    }




    private function compile (string $fileName, $class): Result {
      if (!file_exists($fileName)) {
        return fail(new NotFoundExc("Could not find 'source' file for $class."));
      }

      $source = fopen($fileName, "r");
      $outputFile = __DIR__ . "/compiled-widgets/" . $class . ".comp.js";
      $output = fopen($outputFile, "w");


      $ln = 1;
      $inComment = false;
      $inString = false;
      $closingQuote = "";
      while (!feof($source)) {
        $line = fgets($source);

        $out = "";
        for ($i = 0; $i < strlen($line); $i++) {
          if ($line[$i] == "'" || $line[$i] == '"' || $line[$i] =="`") {
            if ($inString && $closingQuote == $line[$i]) {
              $inString = false;
            } else {
              $inString = true;
              $closingQuote = $line[$i];
            }
          }

          if ($inString == false && match_char_seq($line, $i, "//")) {
            break;
          }

          if ($inString == false && match_char_seq($line, $i, "/*")) {
            $inComment = true;
          }

          if ($inString == false && match_char_seq($line, $i, "*/")) {
            $inComment = false;
            $i += 2;
          }

          if ($inComment == false) {
            $out .= $line[$i];
          }
        }

        $trimmed = rtrim($out);
        if (strlen($trimmed) != 0) {
          fwrite($output, "$trimmed\n");
        }

        $ln++;
      }

      return success($outputFile);
    }






    public function updateRecord (Record $r): Result {
      $recordRes = $this->createRecord($r->files["config"]->filePath, true);
      if ($recordRes->isFailure()) {
        return $recordRes;
      }

      $rec = $recordRes->getSuccess();
      $this->registry[$rec->properties["class"]] = $rec;
      return success($rec);
    }

    private function createRecord (string &$configFile, $forceCreate = false): Result {
      if (!file_exists($configFile)) {
        return fail (new NotFoundExc("File of xml has not been found in " . $configFile));
      }

      $config = require $configFile;

      if (!$forceCreate && (isset($config["properties"]["class"]) && isset($this->registry[$config["properties"]["class"]]))) {
        return fail(new NotUniqueValueExc("Class with " . $config["properties"]["class"] . " name is already defined."));
      }

      $config["properties"]["dir"] = $dir = dirname($configFile);

      $files = [];
      foreach ($config["files"] as $label => $file) {
        $filePath = "$dir/$file";
        if (!file_exists($filePath)) {
          return fail (new NotFoundExc("File not found! Searching for: '$filePath'."));
        }

        $files[$label] = new Watcher($filePath, filemtime($filePath));
      }
      $files["config"] = new Watcher($configFile, filemtime($configFile));



      if (!isset($files["source"])) {
        return fail (new NullPointerExc("Widget's 'source' file is not defined in 'files' array."));
      }



      if (!isset($config["properties"]["class"])) {
        return fail(new NotFoundExc("Could not find 'class' in 'properties' array in '$configFile'."));
      }
      
      $compileRes = $this->compile($files["source"]->filePath, $config["properties"]["class"]);

      if ($compileRes->isFailure()) {
        return $compileRes;
      }

      $config["properties"]["cfn"] = $compileRes->getSuccess();


      $record = new Record($config["properties"], isset($config["imports"]) ? $config["imports"] : [], $files);
      $isValidRes = $record->isValid();
      if ($isValidRes->isFailure()) {
        return $isValidRes;
      }

      return success($record);
    }

    public function register (string $configFile): Result {
      $recordRes = $this->createRecord($configFile);
      if ($recordRes->isFailure()) {
        return $recordRes;
      }

      $r = $recordRes->getSuccess();
      $this->addRecord($r->properties["class"], $r);

      return $recordRes;
    }
  }