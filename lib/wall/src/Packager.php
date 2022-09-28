<?php

  require_once __DIR__ . "/OrderedList.php";
  require_once __DIR__ . "/Watcher.php";
  require_once __DIR__ . "/Record.php";
  require_once __DIR__ . "/xToArray.php";
  require_once __DIR__ . "/../../retval/retval.php";

  const SAVE_FILE = "compiled-registry.json";

  function array_flat (array &$multidimentinal): array {
    $arr = [];

    array_walk_recursive($multidimentinal, function ($e) use (&$arr) {
      $arr[] = $e;
    });

    return $arr;
  }




  class Packager {
    public $registry = [];
    private function addRecord (string $class, Record $r) {
      $this->registry[$class] = $r;
      // var_dump($r);
      file_put_contents(__DIR__ . "/" . SAVE_FILE, json_encode($this->registry));
    } 
    
    public function __construct () {
      $decoded = json_decode(file_get_contents(__DIR__ . "/" . SAVE_FILE));

      if ($decoded == null) return;

      if ($decoded !== null) {
        foreach ($decoded as $className => $record) {
          $this->registry[$className] = Record::parse($record);
        }
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





    public function register (string $xmlFilePath): Result {
      if (!file_exists($xmlFilePath)) {
        return fail (new NotFoundExc("File of xml has not been found in " . $xmlFilePath));
      }

      $dir = dirname($xmlFilePath);
      $xml = new SimpleXMLElement(file_get_contents($xmlFilePath));

      $properties = [];
      $properties["dir"] = $dir;
      $properties = xToArray(get_object_vars($xml->properties), $properties);

      $imports = new OrderedList();
      if (isset($xml->imports)) {
        foreach (get_object_vars($xml->imports->class) as $weigth => $class) {
          $imports->add(new OrderedListNode($weigth, $class));
        }
      }

      $files = [];
      foreach (get_object_vars($xml->files) as $label => $file) {
        $filePath = "$dir/$file";
        if (!file_exists($filePath)) {
          return fail (new NotFoundExc("File not found! Searching for: '$filePath'."));
        }

        $files[$label] = new Watcher ($filePath, filemtime($filePath));
      }
      $files["xml"] = new Watcher ($xmlFilePath, filemtime($xmlFilePath));





      if (!isset($files["source"])) {
        return fail (new NullPointerExc("Source file for component is not defined with <source> tag in <files> tag."));
      }

      $source = fopen($files["source"]->filePath, "r");
      $outputFile = __DIR__ . "/compiled-bricks/" . $properties["class"] . ".comp.js";
      $output = fopen($outputFile, "w");




      $ln = 1;
      while (!feof($source)) {
        $line = fgets($source);
        $background = "white";

        if (preg_match("/.*{%(.+)%}.*/", $line, $matches, PREG_OFFSET_CAPTURE)) {
          $background = "#dbdfff";
          $start = $matches[1][1] - 2;
          $len = strlen($matches[1][0]) + 4;
          
          switch ($matches[1][0]) {
            case 'css': {
              if (!isset($files["styles"]->filePath)) {
                return fail (new NullPointerExc("Styles file for component is not defined with <styles> tag in <files> tag."));
              }

              if (!file_exists($files["styles"]->filePath)) {
                return fail (new NotFoundExc("Styles file has not been found in " . $files["styles"]->filePath));
              }

              $line = substr_replace(
                $line,
                file_get_contents($files["styles"]->filePath),
                $start,
                $len
              );
              break;
            }

            case 'html': {
              if (!isset($files["template"]->filePath)) {
                return fail (new NullPointerExc("Template file for component is not defined with <template> tag in <files> tag."));
              }

              if (!file_exists($files["template"]->filePath)) {
                return fail (new NotFoundExc("Templates file has not been found in " . $files["template"]->filePath));
              }

              $line = substr_replace(
                $line,
                file_get_contents($files["template"]->filePath),
                $start,
                $len
              );
              break;
            }

            case 'template': {
              if (!isset($files["styles"]->filePath)) {
                return fail (new NullPointerExc("Styles file for component is not defined with <styles> tag in <files> tag."));
              }

              if (!file_exists($files["styles"]->filePath)) {
                return fail (new NotFoundExc("Styles file has not been found in " . $files["styles"]->filePath));
              }

              if (!isset($files["template"]->filePath)) {
                return fail (new NullPointerExc("Template file for component is not defined with <template> tag in <files> tag."));
              }

              if (!file_exists($files["template"]->filePath)) {
                return fail (new NotFoundExc("Templates file has not been found in " . $files["template"]->filePath));
              }

              $line = substr_replace(
                $line,
                "<style>" . file_get_contents($files["styles"]->filePath) . "</style>" . file_get_contents($files["template"]->filePath),
                $start,
                $len
              );
              break;
            }
            
            default: {
              return fail (new InvalidArgumentExc("Invalid character at line: $ln; position: " . $matches[1][1]));
              break;
            }
          }
        }

        fwrite($output, $line);

        // echo '<span style="font-family: monospace; white-space: pre; background-color: ' . $background . '">' .htmlspecialchars($line). "</span><br>";
        $ln++;
      }

      $properties["cfn"] = $outputFile;
      $record = new Record($properties, $imports, $files);
      $isValidRes = $record->isValid();
      if ($isValidRes->isFailure()) {
        return $isValidRes;
      }

      $this->addRecord((string)$xml->properties->class, $record);
      return success($record);
    }
  }