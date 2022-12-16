<?php
  
  require_once __DIR__ . "/../../retval/retval.php";
  
  class Generate {
    const CHARSET_URL = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_";
    const CHARSET_NUMBERS = "1234567890";
    
    public static function string (string $charset, int $length): Closure {
      $charsetSize = strlen($charset);
      
      return function () use ($charset, $charsetSize, $length) {
        $string = "";
        for ($i = 0; $i < $length; $i++) {
          $string .= $charset[random_int(0, $charsetSize - 1)];
        }
        return $string;
      };
    }
    
    public static function number (int $min, int $max) {
      return function () use ($min, $max) {
        return random_int($min, $max);
      };
    }
  
    /**
     * @param Closure $generator
     * @param Closure $validator (mixed $value): Result
     * @param int $retries
     * @return Result
     */
    public static function valid (Closure $generator, Closure $validator, int $retries = 1000): Result {
      $value = null;
      while ($value == null && $retries > 0) {
        $rawValue = $generator();
        $validatorResult = $validator($rawValue);
        $validatorResult->succeeded(function ($isTaken) use ($rawValue, &$value) {
          if (!$isTaken) $value = $rawValue;
        });
    
        $retries++;
      }
  
      if ($value == null) {
        return fail(new Exc("Could not generate appropriate value for validator."));
      }
  
      return success($value);
    }
  }