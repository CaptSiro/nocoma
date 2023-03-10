<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/regular-expressions.php";
  require_once __DIR__ . "/../lib/retval/retval.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../models/Theme.php";
  require_once __DIR__ . "/../models/ProfilePicture.php";
  
  $profileRouter = new Router();
  
  
  
  
  $profileRouter->patch("/username", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      if (!preg_match(REGEX_USERNAME, $request->body->get("value"))) {
        $response->json(["error" => "Not a valid username."]);
      }
  
      $sideEffect = User::updateUsername($request->body->get("value"), $request->session->get("user")->ID);
      if ($sideEffect->rowCount === 1) {
        $request->session->modify("user", function (User $user) use ($request) {
          $user->username = $request->body->get("value");
          return $user;
        });
      }
      
      $response->json($sideEffect);
    }
  ]);
  
  
  
  
  $profileRouter->patch("/theme-src", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      $theme = Theme::getBySRC($request->body->get("src"))
        ->forwardFailure($response)
        ->getSuccess();
      
      if (!$theme) {
        $response->fail(new NotFoundExc("This theme does not exist."));
      }
      
      if ($theme->usersID !== $request->session->get("user")->ID && $theme->usersID !== 0) {
        $response->fail(new IllegalArgumentExc("You dont have this theme."));
      }
    
      $sideEffect = User::updateThemeSRC($request->body->get("src"), $request->session->get("user")->ID);
      if ($sideEffect->rowCount === 1) {
        $request->session->modify("user", function (User $user) use ($request, $theme) {
          $user->themesSRC = ($theme->usersID === 0 ? "_" : "") . $request->body->get("src");
          return $user;
        });
      }
    
      $response->json($sideEffect);
    }
  ]);
  
  
  
  
  $profileRouter->post("/picture", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response) {
      $response->json(
        ProfilePicture::save($request->files->get("picture"), $request->session->get("user"))
          ->forwardFailure($response)
          ->getSuccess()
      );
    }
  ]);
  
  
  
  $renderPicture = function (string $path, Request $request, Response $response) {
    $type = Response::getMimeType($path)
      ->forwardFailure($response)
      ->getSuccess();
    
    $response->sendOptimalImage($path, $type, $request);

    $response->setHeader("Content-Type", $type);
    $response->readFile($path);
  };
  $serveUserPicture = function (Request $request, Response $response, Closure $next, Result $userResult) use ($renderPicture) {
    if ($userResult->isFailure()) {
      $renderPicture(__DIR__ . "/../static/images/stock/pfp-user-not-found.png", $request, $response);
    }
    
    /** @var User $user */
    $user = $userResult->getSuccess();
    
    $usersPicture = ProfilePicture::getByUserID($user->ID)
      ->failed(function () use ($request, $response, $renderPicture) {
        $renderPicture(__DIR__ . "/../static/images/stock/pfp.png", $request, $response);
      })
      ->getSuccess();
  
    $renderPicture(HOSTS_DIR . "/$user->website/media/$usersPicture->src$usersPicture->extension", $request, $response);
  };
  
  $profileRouter->get("/picture", [
    Middleware::requireToBeLoggedIn(),
    function (Request $request, Response $response, Closure $next) {
      $next(success($request->session->get("user")));
    },
    $serveUserPicture
  ]);
  
  
  
  
  $profileRouter->get("/picture/:userID", [
    function (Request $request, Response $response, Closure $next) {
      $next(User::get($request->param->get("userID")));
    },
    $serveUserPicture
  ], ["userID" => Router::REGEX_NUMBER]);
  
  
  
  
//  $profileRouter->get("/collect", [function (Request $request, Response $response) {
//    $response->render("collect");
//  }]);
  
  
  
  
  return $profileRouter;