<?php
  
  class Image {
    const DOWN_SCALE_WIDTH = 420;
    static int $colorChannelRounding = 20; // 0-19; 20-39; 40-59; ... 240-255
    const GRAY_TOLERANCE = 15;
    
    const DOMINANT = 0;
    const AVERAGE = 1;
  
    /**
     * @param $filePath
     * @param mixed $a
     * @param mixed $b
     * @return array|false [Image::DOMINANT, Image::AVERAGE]
     */
    public static function getDominantPixel ($filePath, $a = 3, $b = 2) {
      ini_set("memory_limit","512M");
      $scaledResource = self::getScaledImage($filePath);
      if ($scaledResource === false) {
        return false;
      }
      
      $width = imagesx($scaledResource);
      $height = imagesy($scaledResource);
      
      $base = ceil(255 / self::$colorChannelRounding);
      $pixels = new SplFixedArray($base ** 3);
      
      $dominantPixel = null;
      $dominantPixelPoints = PHP_INT_MIN;
      $backgroundPixel = null;
      $backgroundPixelPoints = PHP_INT_MIN;
      
      for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
          $pixel = self::getPixel($scaledResource, $x, $y);
          $index = self::hashPixel($pixel, $base);
          
          $pixelPoints = $pixels->offsetGet($index) ?? [0, 0];
          [$pixelSaturation, $pixelLightness] = self::rgbToSL($pixel);
          
          $pixelLightnessBezier = self::bezierCurve(($pixelLightness > 0.5 ? 1 - $pixelLightness : $pixelLightness) * 2, $a, $b);
          $pixelSaturationBezier = self::bezierCurve($pixelSaturation, $a, $b);
          
          $pixelPoints[0] += $pixelSaturationBezier * $pixelLightnessBezier;
          $pixelPoints[1] += (1 - $pixelLightnessBezier) * (1 - $pixelSaturationBezier);
          
          $pixels->offsetSet($index, $pixelPoints);
          
          if ($dominantPixelPoints < $pixelPoints[0]) {
            $dominantPixelPoints = $pixelPoints[0];
            $dominantPixel = $index;
          }
  
          if ($backgroundPixelPoints < $pixelPoints[1]) {
            $backgroundPixelPoints = $pixelPoints[1];
            $backgroundPixel = $index;
          }
        }
      }
      
      return [
        self::DOMINANT => $dominantPixel === null ? [255, 255, 255] : self::unhashPixel($dominantPixel, $base),
        self::AVERAGE => $backgroundPixel === null ? [0, 0, 0] : self::unhashPixel($backgroundPixel, $base)
      ];
    }
    
    public static function getScaledImage ($filePath) {
      if (!file_exists($filePath)) {
        return false;
      }
      
      $contents = file_get_contents($filePath);
      $imageHandle = imagecreatefromstring($contents);
      
      if ($imageHandle === false) {
        return false;
      }
      
      if (imagesx($imageHandle) <= self::$colorChannelRounding) {
        return $imageHandle;
      }
      
      $scaled = imagescale($imageHandle, self::DOWN_SCALE_WIDTH);
      
      imagedestroy($imageHandle);
      gc_collect_cycles();
      
      return $scaled;
    }
    
    public static function getPixel ($imageResource, $x, $y): SplFixedArray {
      $pixelInt = imagecolorat($imageResource, $x, $y);
      $pixel = new SplFixedArray(3);
      
      $pixel[0] = ($pixelInt >> 16) & 0xFF;
      $pixel[1] = ($pixelInt >> 8) & 0xFF;
      $pixel[2] = $pixelInt & 0xFF;
      
      return $pixel;
    }
    
    public static function hashPixel (SplFixedArray $pixel, int $base): int {
      $hash = 0;
  
      for ($i = 0; $i < 3; $i++) {
        $hash += floor($pixel[$i] / self::$colorChannelRounding) * ($base ** $i);
      }
      
      return $hash;
    }
    
    public static function displayColor ($rgb): string {
      $text = "$rgb[0], $rgb[1], $rgb[2]";
      return "<div style=\"
      background: rgb($text);
      width: 200px;
      height: 200px;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;\">
        <span style=\"color: " . (self::colorLightness($rgb) > 0.5 ? "black" : "white") . ";\">$text</span>
      </div>";
    }
  
    public static function displayColorHSL ($hsl): string {
      $text = round($hsl[0]) . ", " . round($hsl[1] * 100) . "%, " . round($hsl[2] * 100) . "%";
      return "<div style=\"
      background: hsl($text);
      width: 200px;
      height: 200px;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;\">
        <span style=\"color: " . ($hsl[2] > 0.5 ? "black" : "white") . ";\">$text</span>
      </div>";
    }
    
    public static function unhashPixel (int $hash, int $base): SplFixedArray {
      $pixel = new SplFixedArray(3);
      
      for ($i = 0; $i < 3; $i++) {
        $pixel[$i] = (($hash % $base) * self::$colorChannelRounding) + (self::$colorChannelRounding / 2);
        $hash /= $base;
      }
      
      return $pixel;
    }
    
    public static function isGray ($pixel): bool {
      $rg = $pixel[0] - $pixel[1];
      $rb = $pixel[0] - $pixel[2];

      return ($rg < self::GRAY_TOLERANCE && $rg > -self::GRAY_TOLERANCE)
        && ($rb < self::GRAY_TOLERANCE && $rb > -self::GRAY_TOLERANCE);
    }
    
    public static function bezierCurve ($parameter, $a = 3.0, $b = 2.0): float {
      return $parameter * $parameter * ($a - ($b * $parameter));
    }
    
    public static function rgbToSL ($pixel): array {
      $max = max(...$pixel);
      $min = min(...$pixel);
      $x = ($max - $min) / 255;
  
      $lightness = ($max + $min) / 510;
      $saturation = $lightness > 0 && $lightness < 1
        ? ($x / (1 - abs(2 * $lightness - 1)))
        : $lightness;
      
      return [$saturation, $lightness];
    }
  
    /**
     * Returns range from 0-1;
     *
     * @param $pixel
     * @return float
     */
    public static function colorLightness ($pixel): float {
      return (max(...$pixel) + min(...$pixel)) / 510.0;
    }
  }