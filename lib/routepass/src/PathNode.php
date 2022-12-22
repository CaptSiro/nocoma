<?php

  require_once __DIR__ . "/Node.php";
  require_once __DIR__ . "/RequestError.php";

  class PathNode extends Node {
    // breaking chars -.~
    // dict
    //   id => "([0-9]+)"
    //   name => "([a-z_]+)"
    public static function createParamFormat (string $uriPart, array $paramCaptureGroupMap = []): array {
      $dict = [];
      $dictI = 1;

      $format = "/^";
      $param = "";
      $registerParam = function () use (&$format, &$param, &$paramCaptureGroupMap, &$dict, &$dictI) {
        if ($param !== "") {
          $format .= $paramCaptureGroupMap[$param] ?? "([^-.~]+)";
          $dict[$dictI++] = $param;
          $param = "";
        }
      };
      $doAppendToParam = false;
      for ($i = 0; $i < strlen($uriPart); $i++) {
        if ($uriPart[$i] == "-" || $uriPart[$i] == "." || $uriPart[$i] == "~" || $uriPart[$i] == "\\" || $uriPart[$i] == "[") {
          if ($uriPart[$i] == "[" && (isset($uriPart[$i + 1]) && $uriPart[$i + 1] == "]")) {
            if ($param !== "") {
              $format .= $paramCaptureGroupMap[$param] ?? "([^-.~]+)";
              $dict[$dictI++] = $param . "[]";
              $param = "";
            }
            
            $i++;
            continue;
          }
          
          $registerParam();

          if (($uriPart[$i] != "\\")) {
            $format .= $uriPart[$i];
          }
          $doAppendToParam = false;
          continue;
        }

        if ($uriPart[$i] == ":") {
          $registerParam();
          $doAppendToParam = true;
          continue;
        }

        ${$doAppendToParam ? "param" : "format"} .= $uriPart[$i];
      }
  
      $registerParam();
      $format .= "$/";

      return [$format, $dict];
    }
  
    private static function sliceArray (array $array, $startIndex) {
      $newArray = [];
      
      $doAdd = false;
      for ($i = 0; $i < count($array); $i++) {
        if ($i === $startIndex || $doAdd) {
          $doAdd = true;
          $newArray[] = $array[$i];
        }
      }
      
      return $newArray;
    }
    
    
    
    /**
     * @var Node[]
     */
    public $static = [];
    public $parametric = [];
    /**
     * @var Closure[][]
     */
    public $handles = [];
    private $redirectAllToHome = false;
  
  
    public function __construct (string $pathPart, Node $parent) {
      $this->parent = $parent;
      $this->pathPart = $pathPart;
    }
  
  
    
    public function createPath (array $uriParts, array &$paramCaptureGroupMap = []): Node {
      if (empty($uriParts)) {
        return $this;
      }
  
      $part = array_shift($uriParts);
  
      if (isset($this->static[$part])) {
        return $this->static[$part]->createPath($uriParts, $paramCaptureGroupMap);
      }
  
      [$regex, $dict] = self::createParamFormat($part, $paramCaptureGroupMap);
      foreach ($this->parametric as [$reg, $node]) {
        if ($reg === $regex) {
          $node->createPath($uriParts, $paramCaptureGroupMap);
        }
      }
//      if (isset($this->parametric[$regex])) {
//        return $this->parametric[$regex]->createPath($uriParts, $paramCaptureGroupMap);
//      }
  
      //* create new end point
      if (strpos($part, ":") === false) {
        //* static
        $node = new PathNode($part, $this);
        $this->static[$part] = $node;
        return $node->createPath($uriParts, $paramCaptureGroupMap);
      }
  
      //* parametric
      $node = new ParametricPathNode($regex, $this);
      $this->parametric[] = [$regex, $node];
      $node->paramDictionary = $dict;
      return $node->createPath($uriParts, $paramCaptureGroupMap);
    }

    
    
    protected function assign (string &$httpMethod, array &$uriParts, array &$callbacks, array &$paramCaptureGroupMap = []) {
      if (empty($uriParts)) {
        $this->handles[$httpMethod] = $callbacks;
        return;
      }

      $part = array_shift($uriParts);
      if ($part[0] == "*" && strlen($part) == 1) {
        $this->redirectAllToHome = true;
        $this->handles[$httpMethod] = $callbacks;
        return;
      }

      if (isset($this->static[$part])) {
        $this->static[$part]->assign($httpMethod, $uriParts, $callbacks, $paramCaptureGroupMap);
        return;
      }
      
      [$regex, $dict] = self::createParamFormat($part, $paramCaptureGroupMap);
      foreach ($this->parametric as [$reg, $node]) {
        if ($reg === $regex  && $node->paramDictionary === $dict) {
          $node->createPath($uriParts, $paramCaptureGroupMap);
        }
      }
//      if (isset($this->parametric[$regex]) && $this->parametric[$regex]->paramDictionary === $dict) {
//        $this->parametric[$regex]->assign($httpMethod, $uriParts, $callbacks, $paramCaptureGroupMap);
//        return;
//      }
      
      //* create new end point
      
      if (strpos($part, ":") === false) {
        //* static
        $node = new PathNode($part, $this);
        $node->assign($httpMethod, $uriParts, $callbacks, $paramCaptureGroupMap);
        $this->static[$part] = $node;
        return;
      }

      //* parametric
//      var_dump("___new___");
//      var_dump($part, $regex);
      $node = new ParametricPathNode($regex, $this);
      $node->assign($httpMethod, $uriParts, $callbacks, $paramCaptureGroupMap);
      $this->parametric[] = [$regex, $node];
      $node->paramDictionary = $dict;
    }
    
    
    
    protected function setMethod (string &$httpMethod, array &$callbacks) {
      $this->handles[$httpMethod] = $callbacks;
    }
  
    
  
    protected function execute (array &$uri, int $uriIndex, Request &$request, Response &$response) {
      if (!isset($uri[$uriIndex])) {
        $this->callHandlesClosures($request, $response);
        return;
      }

      $part = $uri[$uriIndex];
      $request->trace[] = $part;
      if (isset($this->static[$part])) {
        $this->static[$part]->execute($uri, $uriIndex + 1, $request, $response);
        return;
      }
      
      // breaking chars [-.~]
      /**
       * @var $regex string
       * @var $node ParametricPathNode
       */
      foreach ($this->parametric as [$regex, $node]) {
        if (preg_match($regex, $part, $matches)) {
          if ($node instanceof Router) {
            $node = &$node->home;
          }
          
          $removeRegister = [];
          
          foreach ($node->paramDictionary as $key => $param) {
            $paramLength = strlen($param);
            if ($param[$paramLength - 2] == "[" && $param[$paramLength - 1] == "]") {
              $shortHand = substr($param, 0, -2);
              
              if ($request->param->isset($shortHand)) {
                $request->param->modify($shortHand, function ($value) use ($matches, $key, &$removeRegister, $shortHand) {
                  $removeRegister[$shortHand][] = count($value);
                  $value[] = $matches[$key];
                  return $value;
                });
              } else {
                $request->param->set($shortHand, [$matches[$key]]);
                $removeRegister[$shortHand] = [0];
              }
              continue;
            }
            
            $request->param->set($param, $matches[$key]);
            $removeRegister[$param] = -1;
          }
          
          $node->execute($uri, $uriIndex + 1, $request, $response);
          
          foreach ($removeRegister as $remove => $indexes) {
            if ($indexes === -1) {
              $request->param->unset($remove);
              continue;
            }
            
            $request->param->modify($remove, function ($value) use ($indexes) {
              foreach ($indexes as $index) {
                $value[$index] = null;
              }
              return $value;
            });
          }
        }
      }
      
      if ($this->redirectAllToHome) {
        $slicedURI = self::sliceArray($uri, $uriIndex + 1);
        $request->remainingURI = "$part" . (count($slicedURI) == 0 ? "" : ("/" . join("/", $slicedURI)));
        $this->callHandlesClosures($request, $response);
      }
  
      if (!$request->getState() == HomeRouter::REQUEST_SERVED) {
        $request->setState(HomeRouter::ERROR_ENDPOINT_DOES_NOT_EXISTS);
      }
    
//      $request->homeRouter->dispathError(
//        HomeRouter::ERROR_ENDPOINT_DOES_NOT_EXISTS,
//        new RequestError("Endpoint does not exist for '$request->fullURI'", $request, $response)
//      );
    }
    
    
    
    private static function extractEndpoints (array $array): array {
      $accumulator = [];
      
      foreach ($array as $key => $value) {
        if ($value instanceof ParametricPathNode) {
          $key .= " -> " . join(", ", $value->paramDictionary);
        }
        
        if ($value instanceof Node) {
          $accumulator[$key] = $value->getEndpoints();
          continue;
        }
  
        $accumulator[$key] = join("; ", array_keys($value->handles));
      }
      
      return $accumulator;
    }
    public function getEndpoints(): array {
      if ($this->redirectAllToHome) return [];
      
      return [
        "handles" => join(", ", array_keys($this->handles)),
        "static" => self::extractEndpoints($this->static),
        "parametric" => self::extractEndpoints($this->parametric),
      ];
    }
  
  
    private function callHandlesClosures (Request $request, Response $response) {
      if (!isset($this->handles[$_SERVER["REQUEST_METHOD"]])) {
        if (!$request->getState() == HomeRouter::REQUEST_SERVED) {
//          var_dump($this);
          $request->setState(HomeRouter::ERROR_HTTP_METHOD_NOT_IMPLEMENTED);
        }
//        $request->homeRouter->dispathError(
//          HomeRouter::ERROR_HTTP_METHOD_NOT_IMPLEMENTED,
//          new RequestError("HTTP method: '$_SERVER[REQUEST_METHOD]' is not implemented for '$request->fullURI'", $request, $response)
//        );
        return;
      }
  
      $request->setState(HomeRouter::REQUEST_SERVED);
      
      $doNext = false;
      $argumentsForNextHandler = [];
      $nextFunc = function (...$arguments) use (&$doNext, &$argumentsForNextHandler) {
        $doNext = true;
        $argumentsForNextHandler = $arguments;
      };
  
      foreach ($this->handles[$_SERVER["REQUEST_METHOD"]] as $cb) {
        $cb($request, $response, $nextFunc, ...$argumentsForNextHandler);
    
        if ($doNext) {
          $doNext = false;
          continue;
        }
    
        if ($request->homeRouter->getFlag(HomeRouter::FLAG_RESPONSE_AUTO_FLUSH)) {
          $response->flush();
        }
    
        break;
      }
    }
  }

  require_once __DIR__ . "/ParametricPathNode.php";