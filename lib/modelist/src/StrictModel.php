<?php 

  require_once(__DIR__ . "/Model.php");


  abstract class StrictModel extends Model {
    abstract protected static function getNumberProps (): array;
    abstract protected static function getBooleanProps (): array;

    public static function numberVal ($string) {
      if (strpos($string, ".") !== false) {
        return floatval($string);
      } else {
        return intval($string);
      }
    }
  
    /**
     * @param $objectOrArray
     * @return array|mixed|false if type of $objectOrArray is not object or array, false is returned
     */
    public static function parseProps ($objectOrArray) {
      $type = gettype($objectOrArray);
      if (!($type === "object" || $type === "array")) {
        return false;
      }
      
      $cb = function ($obj) {
        foreach ($obj as $key => $value) {
          if (in_array($key, static::getNumberProps())) {
            if (isset($obj->$key)) {
              $obj->$key = self::numberVal($value);
            }
          }

          if (in_array($key, static::getBooleanProps())) {
            if (isset($obj->$key)) {
              $obj->$key = boolval($value);
            }
          }
        }

        return $obj;
      };

      if (is_array($objectOrArray)) {
        return array_map($cb, $objectOrArray);
      }

      return $cb($objectOrArray);
    }
  }