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
        LEFT JOIN dynamicthemes ON media.src = dynamicthemes.mediaSRC
        WHERE media.src = :src
            AND mimeContentType LIKE 'image/%'
            AND dynamicthemes.a = :a
            AND dynamicthemes.b = :b",
        Count::class,
        [
          new DatabaseParam("src", $imageSRC, PDO::PARAM_STR),
          new DatabaseParam("a", $a),
          new DatabaseParam("b", $b),
        ]
      ));
      
      if ($themeCount->amount !== 0) {
        $themeSRC = Database::get()->fetch(
          "SELECT themesSRC FROM `media`
          LEFT JOIN dynamicthemes ON media.src = dynamicthemes.mediaSRC
          WHERE media.src = :src
            AND mimeContentType LIKE 'image/%'
            AND dynamicthemes.a = :a
            AND dynamicthemes.b = :b",
          stdClass::class,
          [
            new DatabaseParam("src", $imageSRC, PDO::PARAM_STR),
            new DatabaseParam("a", $a),
            new DatabaseParam("b", $b),
          ]
        )->themesSRC;
        
        Database::get()->statement(
          "UPDATE themes SET `name` = :name WHERE src = :src",
          [
            new DatabaseParam("name", $name, PDO::PARAM_STR),
            new DatabaseParam("src", $themeSRC, PDO::PARAM_STR),
          ]
        );
        
        return success($themeSRC);
      }
      
//      $requests[] = $website;
//      file_put_contents(self::CREATION_REQUESTS, json_encode($requests));
      
      $filePath = glob(HOSTS_DIR . "/$website/media/$imageSRC.*");
      if ($filePath === false) {
        return fail(new NotFoundExc("File does not exist"));
      }
      
      $imageColors = Image::getDominantPixel($filePath[0], $a, $b);
      if ($imageColors === false) {
        return fail(new Exc("Could not extract colors from image."));
      }
  
      $isContainerLightColor = Image::colorLightness($imageColors[Image::AVERAGE]) > 0.5;
      $containerShades = self::createShades(
        $imageColors[Image::AVERAGE],
        $isContainerLightColor ? self::COLOR_WHITE : self::COLOR_BLACK,
      );
      
      if (!$isContainerLightColor) {
        $containerShades = array_reverse($containerShades);
      }
      
      $containerTextShades = self::createShades(
        $isContainerLightColor ? self::COLOR_BLACK : self::COLOR_WHITE,
        $imageColors[Image::AVERAGE],
        3,
      );
  
      $isOppositeLightColor = Image::colorLightness($imageColors[Image::DOMINANT]) > 0.7;
      $oppositeShades = self::createShades(
        $imageColors[Image::DOMINANT],
        !$isOppositeLightColor ? self::COLOR_WHITE : self::COLOR_BLACK,
        4
      );
  
      if (!$isOppositeLightColor) {
        $oppositeShades = array_reverse($oppositeShades);
      }
      
      $oppositeTextShades = self::createShades(
        !$isOppositeLightColor ? self::COLOR_BLACK : self::COLOR_WHITE,
        $imageColors[Image::DOMINANT],
        2,
      );
  
//      echo "<div style='display: flex;'>";
//        self::displayShade($containerTextShades);
//        self::displayShade($containerShades);
//        self::displayShade($oppositeShades);
//        self::displayShade($oppositeTextShades);
//      echo "</div>";
      
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
        $content .= "  \n$attributeName: $value;";
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
        "INSERT INTO dynamicthemes (themesSRC, mediaSRC, a, b)
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
    
    private static function displayShade ($shades) {
      echo "<div style='display: flex; flex-direction: column'>";
      foreach ($shades as $shade) {
        echo Image::displayColor($shade);
      }
      echo "</div>";
    }
    
    private static function margeToAttributes (&$attributes, $attributeName, $shades) {
      foreach ($shades as $index => $color) {
        $attributes[$attributeName . $index] = "rgb($color[0], $color[1], $color[2])";
      }
    }
  
    /**
     * @param $start
     * @param $end
     * @param int $count
     * @return array
     */
    private static function createShades ($start, $end, int $count = 5): array {
      $shades = [$start];
      $lightness = min(Image::colorLightness($start), Image::colorLightness($end))
        / max(Image::colorLightness($start), Image::colorLightness($end));
      $nextFraction = self::map(
        $lightness,
        0,
        1,
        0.2,
        0.05
      );
      $step = self::map(
        $lightness,
        0,
        1,
        0.1,
        0.324
      );
      
      
      $latestStart = $start;
      $latestEnd = $end;
      for ($i = 0; $i < $count - 1; $i++) {
        $next = [
          self::shade($latestStart[0], $latestEnd[0], $step),
          self::shade($latestStart[1], $latestEnd[1], $step),
          self::shade($latestStart[2], $latestEnd[2], $step),
        ];
        $latestEnd = [
          $latestEnd[0] - ($next[0] * $nextFraction),
          $latestEnd[1] - ($next[1] * $nextFraction),
          $latestEnd[2] - ($next[2] * $nextFraction),
        ];
        
//        $lightness = min(Image::colorLightness($next), Image::colorLightness($end))
//          / max(Image::colorLightness($next), Image::colorLightness($end));
//        $nextFraction = self::map(
//          $lightness,
//          0,
//          255,
//          0.2,
//          0
//        );
//        $step = self::map(
//          $lightness,
//          0,
//          255,
//          0.1,
//          0.4
//        );
        
        $shades[] = $next;
        $latestStart = $next;
      }
      
      return $shades;
    }
    
    private static function shade ($channel, $towards, $step): float {
      return round($channel + (($towards - $channel) * $step));
    }
    
    private static function map ($value, $start1, $end1, $start2, $end2) {
      return (($value - $start1) / ($end1 - $start1)) * ($end2 - $start2) + $start2;
    }
  }