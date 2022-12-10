<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  require_once __DIR__ . "/../lib/paths.php";
  require_once __DIR__ . "/../lib/dotenv/dotenv.php";
  require_once __DIR__ . "/../lib/retval/retval.php";
  require_once __DIR__ . "/../lib/susmail/susmail.php";
  
  
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../models/TimeoutMail.php";
  
  
  require_once __DIR__ . "/Middleware.php";
  
  
  $env = new Env(ENV_FILE);
  $authRouter = new Router();
  
  
  
  
  $authRouter->get("/", [
    Middleware::requireToBeLoggedOut(Middleware::RESPONSE_REDIRECT),
    function (Request $request, Response $response) use ($env) {
      $response->render("login-register", ["host" => $env->HOST_NAME]);
    }
  ]);
  
  
  
  
  $authRouter->get("/background", [function (Request $request, Response $response) {
    $response->json(
      array_values(
        array_diff(
          scandir(__DIR__ . "/../static/images/login-register-bgs"),
          ['.', '..']
        )
      )
    );
  }]);
  
  
  
  
  $authRouter->post("/login", [
    Middleware::requireToBeLoggedOut(Middleware::RESPONSE_JSON),
    function (Request $request, Response $response) {
      $userRes = User::getByEmail($request->body->get("email"));
      $userRes->failed(function () use ($response) {
        $response->json((object)["error" => "Email or password does not match."]);
      });
      
      /** @var User $user */
      $user = $userRes->getSuccess();
      $compareResult = $user->comparePassword($request->body->get("password"));
      $compareResult->forwardFailure($response);
      
      $compareResult->succeeded(function ($doesLoginMatch) use ($request, $response, $user) {
        if (!$doesLoginMatch) {
          $response->json((object)["error" => "Email or password does not match."]);
          return;
        }
        
        $request->session->set("user", $user);
        
        if (!$user->isVerified) {
          $response->json((object)["next" => "code-verification"]);
        }
        
        $redirectURL = Response::createRedirectURL("/dashboard/user");
        
        if ($user->level === Middleware::LEVEL_ADMIN) {
          $redirectURL = Response::createRedirectURL("/dashboard/admin");
        }
        
        $response->json((object)["redirect" => $redirectURL]);
      });
    }
  ]);
  
  
  
  
  $emailRegex = "/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/";
  $websiteRegex = "/^[a-zA-Z0-9-_]{1,64}$/";
  $passwordRegex = "/(?=.{8,})(?=.*[a-zA-Z]+)(?=.*[0-9]+)(?=.*[!\"#$%&'()*+,-.\/:;<=>?@\\^_\[\]`{|}~]+)^[a-zA-Z0-9!\"#$%&'()*+,-.\/:;<=>?@\\^_\[\]`{|}~]+$/";
  
  $isTakenFunctionFactory = function (string $propertyName, Response $response) {
    return function (bool $isTaken) use ($response, $propertyName) {
      if ($isTaken) {
        $response->json((object) ["error" => "$propertyName is taken."]);
      }
      
      // pass
    };
  };
  $authRouter->post("/register", [
    Middleware::requireToBeLoggedOut(Middleware::RESPONSE_JSON),
    function (Request $request, Response $response) use ($emailRegex, $websiteRegex, $passwordRegex, $isTakenFunctionFactory) {
      if (!preg_match($emailRegex, $request->body->get("email"))) {
        $response->json((object)["error" => "Not a valid email."]);
      }
    
      if (!preg_match($websiteRegex, $request->body->get("website"))) {
        $response->json((object)["error" => "Not a valid website domain."]);
      }
    
      if (!preg_match($passwordRegex, $request->body->get("password"))) {
        $response->json((object)["error" => "Password is not strong enough."]);
      }
      
      $emailResult = User::isEmailTaken($request->body->get("email"));
      $websiteResult = User::isWebsiteTaken($request->body->get("website"));
      
      $emailResult->forwardFailure($response);
      $websiteResult->forwardFailure($response);
      
      $emailResult->succeeded($isTakenFunctionFactory("Email", $response));
      $websiteResult->succeeded($isTakenFunctionFactory("Website", $response));
      
      // all good, register user
      
      $registerSideEffect = User::register(
        $request->body->get("email"),
        $request->body->get("website"),
        password_hash($request->body->get("password"), PASSWORD_DEFAULT)
      );
      $userResult = User::get($registerSideEffect->lastInsertedID);
      
      $userResult->either(
        function (User $user) use ($request) {
          $request->session->set("user", $user);
        },
        function (Exception $exception) use ($response) {
          $response->json($exception);
        }
      );
      
      $response->json((object) ["next" => "code-verification"]);
    }
  ]);
  
  
  
  
  $authRouter->delete("/logout", [
    Middleware::requireToBeLoggedIn(Middleware::RESPONSE_JSON),
    function (Request $request, Response $response) {
      $request->session->unset("user");
      $response->json((object) ["redirect" => Response::createRedirectURL("/")]);
    }
  ]);
  
  
  
  
  $authRouter->get("/verification-code", [function (Request $request, Response $response) {
    $userResult = User::get(intval($request->session->get("user")->ID));
    $userResult->forwardFailure($response);
    
    /** @var User $user */
    $user = $userResult->getSuccess();
    TimeoutMail::removeCodesFor($user->ID);
    
    $codeResult = TimeoutMail::generate(TimeoutMail::codeGenerator(), TimeoutMail::isCodeTaken());
    $codeResult->forwardFailure($response);
    
    $insertCodeResult = TimeoutMail::insertCode($codeResult->getSuccess(), $user->ID);
    $insertCodeResult->forwardFailure($response);
    
    MailTemplate::verifyAccount($user, $codeResult->getSuccess())->send();
    
    $response->json((object) ["message" => "Code has been sent."]);
  }]);
  
  
  
  
  $authRouter->post("/password-recovery-email", [function (Request $request, Response $response) {
    $userResult = User::getByEmail($request->body->get("email"));
    $userResult->forwardFailure($response);
    
    $argumentResult = TimeoutMail::generate(TimeoutMail::argumentGenerator(), TimeoutMail::isURLArgumentTaken());
    $argumentResult->forwardFailure($response);
    
    $argument = $argumentResult->getSuccess();
    $user = $userResult->getSuccess();
  
    $insertRes = TimeoutMail::insertUrlArg($argument, $user->ID);
    $insertRes->forwardFailure($response);
  
    MailTemplate::passwordRecovery(
      $user,
      (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on"
        ? "https"
        : "http") . "://$_SERVER[HTTP_HOST]" . Response::createRedirectURL("/auth/password-recovery/$argument")
    )->send();
  
    $response->json((object)["next" => "forgotten-password-2"]);
  }]);
  
  
  
  $authRouter->get("/password-recovery", [function (Request $request, Response $response) {
    $response->render("password-recovery/failure", [
      "message" => "You need to use link specially created for you.",
      "replenishLink" => Response::createRedirectURL("/auth")
    ]);
  }]);
  
  $authRouter->get("/password-recovery/:argument", [function (Request $request, Response $response) {
    $userResult = TimeoutMail::getUserWithUA($request->param->get("argument"));
    $userResult->failed(function (Exc $exception) use ($response) {
      $response->render("password-recovery/failure", [
        "message" => $exception->getMessage(),
        "replenishLink" => Response::createRedirectURL("/auth")
      ]);
    });
    
    $request->session->set("passwordRecoveryUser", $userResult->getSuccess());
    $response->render("password-recovery/success", ["replenishLink" => Response::createRedirectURL("/auth")]);
  }], ["argument" => "([0-9a-zA-Z_-]+)"]);
  
  
  
  
  $authRouter->patch("/password", [function (Request $request, Response $response) use ($passwordRegex) {
    if (!preg_match($passwordRegex, $request->body->get("password"))) {
      $response->json(new InvalidArgumentExc("Password is not strong enough."));
    }
  
    if (!$request->session->isset("passwordRecoveryUser")) {
      $response->json(new InvalidArgumentExc("Unknown user has requested password recovery"));
    }
  
    TimeoutMail::removeUA($request->body->get("argument"));
    User::updatePassword(
      password_hash($request->body->get("password"), PASSWORD_DEFAULT),
      $request->session->get("passwordRecoveryUser")->ID
    );
  
    $request->session->unset("passwordRecoveryUser");
  
    $response->json((object)["next" => "success"]);
  }]);
  
  
  
  
  $authRouter->patch("/verification", [function (Request $request, Response $response) {
    $userResult = TimeoutMail::getUserWithCode($request->body->get("code"));
    $userResult->forwardFailure($response);
  
    $user = $userResult->getSuccess();
    User::verify($user->ID);
    //TODO: users personal 'domain'
//    mkdir(__DIR__ . "/../../hosts/" . $user->website);
  
    $removalResult = TimeoutMail::removeCode($request->body->get("code"));
    $removalResult->forwardFailure($response);
  
    $response->json((object)["redirect" => Response::createRedirectURL("/dashboard/user")]);
  }]);
  
  
  
  
  
  return $authRouter;