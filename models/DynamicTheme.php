<?php
  
  require_once __DIR__ . "/../lib/modelist/modelist.php";
  require_once __DIR__ . "/../lib/retval/retval.php";
  require_once __DIR__ . "/../lib/image-tinter/image-tinter.php";
  require_once __DIR__ . "/../lib/paths.php";
  
  require_once __DIR__ . "/Count.php";
  require_once __DIR__ . "/Theme.php";
  require_once __DIR__ . "/Media.php";
  
  class DynamicTheme extends StrictModel {
    protected $dynamicThemesID, $usersID, $name, $themesSRC, $mediaSRC;
  
    protected static function getNumberProps (): array { return ["usersID", "dynamicThemesID"]; }
    protected static function getBooleanProps (): array { return []; }
  
    const TABLE_NAME = "dynamicThemes";
    const ALL_COLUMNS = ["dynamicThemesID", "themesSRC", "mediaSRC"];
    
    
    const CREATION_REQUESTS = __DIR__ . "/dynamic-theme-requests.json";
    const COLOR_BLACK = [0, 0, 0];
    const COLOR_WHITE = [255, 255, 255];
    
    public static function createFrom (string $name, string $imageSRC, string $website, $a = 3, $b = 2): Result {
//      $requests = json_decode(file_get_contents(self::CREATION_REQUESTS));
//
//      if (is_array($requests) && in_array($website, $requests)) {
//        return fail(new IllegalArgumentExc("Cannot request dynamic theme creation when one is already running."));
//      }
      
      $themeCount = Count::parseProps(Database::get()->fetch(
        "SELECT COUNT(media.src) amount FROM `media`
        LEFT JOIN dynamicThemes ON media.src = dynamicThemes.mediaSRC
        WHERE media.src = :src
            AND mimeContentType LIKE 'image/%'
            AND dynamicThemes.a = :a
            AND dynamicThemes.b = :b",
        Count::class,
        [
          new DatabaseParam("src", $imageSRC, PDO::PARAM_STR),
          new DatabaseParam("a", $a),
          new DatabaseParam("b", $b),
        ]
      ));
      
      if ($themeCount->amount !== 0) {
        return fail(new InvalidArgumentExc("Theme already exists."));
//        $themeSRC = Database::get()->fetch(
//          "SELECT themesSRC FROM `media`
//          LEFT JOIN dynamicThemes ON media.src = dynamicThemes.mediaSRC
//          WHERE media.src = :src
//            AND mimeContentType LIKE 'image/%'
//            AND dynamicThemes.a = :a
//            AND dynamicThemes.b = :b",
//          stdClass::class,
//          [
//            new DatabaseParam("src", $imageSRC, PDO::PARAM_STR),
//            new DatabaseParam("a", $a),
//            new DatabaseParam("b", $b),
//          ]
//        )->themesSRC;
//
//        Database::get()->statement(
//          "UPDATE themes SET `name` = :name WHERE src = :src",
//          [
//            new DatabaseParam("name", $name, PDO::PARAM_STR),
//            new DatabaseParam("src", $themeSRC, PDO::PARAM_STR),
//          ]
//        );
//
//        return success($themeSRC);
      }
      
//      $requests[] = $website;
//      file_put_contents(self::CREATION_REQUESTS, json_encode($requests));
      
      $filePath = glob(HOSTS_DIR . "/$website/media/$imageSRC.*");
      if ($filePath === false || !isset($filePath[0])) {
        return fail(new NotFoundExc("File does not exist"));
      }
      
      
      
      $imageColors = Image::getDominantPixel($filePath[0], $a, $b);
      if ($imageColors === false) {
        return fail(new Exc("Could not extract colors from image."));
      }
  
      
      
      $containerHSL = self::rgbToHSL($imageColors[Image::AVERAGE]);
      $isContainerLightColor = $containerHSL[self::HSL_LIGHTNESS] > 0.5;
      $containerShades = self::createShades(
        $containerHSL,
        $isContainerLightColor
          ? self::SHADE_DARK()
          : self::SHADE_LIGHT(),
      );
      
      if ($isContainerLightColor) {
        // $containerShades = array_reverse($containerShades);
      }
      
      $containerAverageLightness = self::averageLightness($containerShades);
      $containerTextShades = self::createShades(
        $containerAverageLightness > 0.5
          ? [$containerHSL[self::HSL_HUE], 0, 0]
          : [$containerHSL[self::HSL_HUE], 1, 1],
        $containerAverageLightness < 0.5
          ? self::SHADE_DARK()
          : self::SHADE_LIGHT(),
        3,
      );
  
      $oppositeHSL = self::rgbToHSL($imageColors[Image::DOMINANT]);
      $isOppositeLightColor = $oppositeHSL[self::HSL_LIGHTNESS] > 0.5;
      $oppositeShades = self::createShades(
        $oppositeHSL,
        $isOppositeLightColor
          ? self::SHADE_DARK()
          : self::SHADE_LIGHT(),
        4
      );
  
      //* that's just how it is, thank myself in the past when he came up with this "BRILLIANT" idea
      if (!$isOppositeLightColor) {
        $oppositeShades = array_reverse($oppositeShades);
      }
  
      $oppositeAverageLightness = self::averageLightness($oppositeShades);
      $oppositeTextShades = self::createShades(
        $oppositeAverageLightness > 0.5
          ? [$oppositeHSL[self::HSL_HUE], 0, 0]
          : [$oppositeHSL[self::HSL_HUE], 1, 1],
        $oppositeAverageLightness < 0.5
          ? self::SHADE_DARK()
          : self::SHADE_LIGHT(),
        2,
      );
  
//      echo "<div style='display: flex;'>";
//        self::displayShades($containerTextShades);
//        self::displayShades($containerShades);
//        self::displayShades($oppositeShades);
//        self::displayShades($oppositeTextShades);
//      echo "</div>";
//
//      return success("nice");
      
      $attributes = [];
      self::margeToAttributes(
        $attributes,
        "--text-color-",
        $containerTextShades
      );
      self::margeToAttributes(
        $attributes,
        "--text-color-opposite-",
        $oppositeTextShades
      );
      self::margeToAttributes(
        $attributes,
        "--container-",
        $containerShades
      );
      self::margeToAttributes(
        $attributes,
        "--container-opposite-",
        $oppositeShades
      );
      
      
      
      
      $content = ":root {";
      foreach ($attributes as $attributeName => $value) {
        $content .= "\n  $attributeName: $value;";
      }
      $content .= "\n}";
      
      
  
      $sourceResult = Generate::valid(
        Generate::string(Generate::CHARSET_URL, Media::SRC_LENGTH_LASTING),
        Media::isSRCValid(HOSTS_DIR . "/$website/media/")
      );
  
      if ($sourceResult->isFailure()) {
        return $sourceResult;
      }
      
      $filePath = HOSTS_DIR . "/$website/media/" . $sourceResult->getSuccess() . ".css";
      file_put_contents($filePath, $content);
      $footprint = sha1_file($filePath);
  
      $userResult = User::getByWebsite($website);
      if ($userResult->isFailure()) {
        return $userResult;
      }
      /**
       * @var User $user
       */
      $user = $userResult->getSuccess();
  
      $sideEffect = Database::get()->statement(
        "INSERT INTO `themes` (`src`, `usersID`, `hash`, `name`)
        VALUE (:src, :userID, :hash, :name)",
        [
          new DatabaseParam("src", $sourceResult->getSuccess(), PDO::PARAM_STR),
          new DatabaseParam("name", $name, PDO::PARAM_STR),
          new DatabaseParam("userID", $user->ID),
          new DatabaseParam("hash", $footprint, PDO::PARAM_STR),
        ]
      );
      
      if ($sideEffect->rowCount != 1) {
        return fail(new Exc("Failed to insert into database."));
      }
      
      $sideEffect = Database::get()->statement(
        "INSERT INTO dynamicThemes (themesSRC, mediaSRC, a, b)
        VALUE (:themesSRC, :mediaSRC, :a, :b)",
        [
          new DatabaseParam("themesSRC", $sourceResult->getSuccess(), PDO::PARAM_STR),
          new DatabaseParam("mediaSRC", $imageSRC, PDO::PARAM_STR),
          new DatabaseParam("a", $a),
          new DatabaseParam("b", $b),
        ]
      );
  
      if ($sideEffect->rowCount != 1) {
        return fail(new Exc("Failed to insert into database."));
      }
      
      return success($sourceResult->getSuccess());
    }
    
    private static function averageLightness ($colors) {
      $count = 0;
      $lightnessSum = 0;
      
      $index = count($colors);
      foreach ($colors as $color) {
        $count += $index;
        $lightnessSum += $index * $color[self::HSL_LIGHTNESS];
        $index--;
      }
      
      return $lightnessSum / $count;
    }
    
    private static function displayShades ($shades) {
      echo "<div style='display: flex; flex-direction: column'>";
      foreach ($shades as $shade) {
        echo Image::displayColorHSL($shade);
      }
      echo "</div>";
    }
    
    private static function margeToAttributes (&$attributes, $attributeName, $shades) {
      foreach ($shades as $index => $color) {
        $attributes[$attributeName . $index] = "hsl(".$color[0].", ".$color[1] * 100 . "%, ".$color[2] * 100 . "%)";
      }
    }
  
    
    public static function SHADE_DARK (): Closure {
      return function ($hsl) {
        $shaded =  [
          self::HSL_HUE => $hsl[self::HSL_HUE] - 3,
          self::HSL_SATURATION => $hsl[self::HSL_SATURATION] + 0.03,
          self::HSL_LIGHTNESS => $hsl[self::HSL_LIGHTNESS] - 0.04
        ];
        
        if ($shaded[self::HSL_SATURATION] > 1) {
          $shaded[self::HSL_LIGHTNESS] -= 0.04;
        }
  
        if ($shaded[self::HSL_LIGHTNESS] > 1) {
          $shaded[self::HSL_SATURATION] += 0.3;
        }
        
        return $shaded;
      };
    }
  
    public static function SHADE_LIGHT (): Closure {
      return function ($hsl) {
        $shaded =  [
          self::HSL_HUE => $hsl[self::HSL_HUE] + 3,
          self::HSL_SATURATION => $hsl[self::HSL_SATURATION] - 0.03,
          self::HSL_LIGHTNESS => $hsl[self::HSL_LIGHTNESS] + 0.04
        ];
  
        if ($shaded[self::HSL_SATURATION] > 1) {
          $shaded[self::HSL_LIGHTNESS] += 0.04;
        }
  
        if ($shaded[self::HSL_LIGHTNESS] > 1) {
          $shaded[self::HSL_SATURATION] -= 0.3;
        }
  
        return $shaded;
      };
    }
    
    /**
     * @param $hsl
     * @param Closure $shader
     * @param int $count
     * @return array
     */
    private static function createShades ($hsl, Closure $shader, int $count = 5): array {
      $shades = [$hsl];
      
      $last = $hsl;
      for ($i = 0; $i < $count - 1; $i++) {
        $productHSL = $shader($last);
  
        if ($productHSL[self::HSL_HUE] > 360) {
          $productHSL[self::HSL_HUE] -= 360;
        }
  
        if ($productHSL[self::HSL_HUE] < 0) {
          $productHSL[self::HSL_HUE] += 360;
        }
  
        $next = [
          $productHSL[self::HSL_HUE],
          max(min($productHSL[self::HSL_SATURATION], 1), 0),
          max(min($productHSL[self::HSL_LIGHTNESS], 1), 0)
        ];
        $shades[] = $next;
        $last = $next;
      }
      
      return $shades;
    }
    
    const HSL_HUE = 0;
    const HSL_SATURATION = 1;
    const HSL_LIGHTNESS = 2;
    private static function rgbToHSL ($rgb): array {
      $red = (float)($rgb[0] / 255);
      $green = (float)($rgb[1] / 255);
      $blue = (float)($rgb[2] / 255);
      
      $max = max($red, $green, $blue);
      $min = min($red, $green, $blue);
      
      $delta = $max - $min;
      $lightness = ($min + $max) / 2;
      
      if ($max == $min) {
        return [0, 0, $lightness];
      }
      
      $saturation = $lightness > 0.5
        ? $delta / (2 - $max - $min)
        : $delta / ($max + $min);
      
      switch ($max) {
        case $red: $hue = ($green - $blue) / $delta + ($green < $blue ? 6 : 0); break;
        case $green: $hue = ($blue - $red) / $delta + 2; break;
        case $blue: $hue = ($red - $green) / $delta + 4; break;
      }
      
      
      
      return [360 * (($hue ?? 0) / 6), $saturation, $lightness];
    }
    
    private static function map ($value, $start1, $end1, $start2, $end2) {
      return (($value - $start1) / ($end1 - $start1)) * ($end2 - $start2) + $start2;
    }
  }