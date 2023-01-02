<?php
  
  require_once __DIR__ . "/../models/User.php";
  
  
  class Middleware {
    const RESPONSE_TEXT = 0;
    const RESPONSE_JSON = 1;
    const RESPONSE_REDIRECT = 2;
    const RESPONSE_REDIRECT_DASHBOARD_MAP = [
      Middleware::LEVEL_ADMIN => "/dashboard/admin",
      Middleware::LEVEL_USER => "/dashboard/user"
    ];
    
    private static $defaultResponseType = self::RESPONSE_TEXT;
    /**
     * @param int $defaultResponseType
     */
    public static function setDefaultResponseType(int $defaultResponseType): void {
      self::$defaultResponseType = $defaultResponseType;
    }
    
    
    public static function sessionStart ($id = null, array $sessionParams = []) {
      return function (Request $request, Response $response, Closure $next) use ($id, $sessionParams) {
        if ($request->loadSession($id, $sessionParams)) $next();
      };
    }
    
    
    public static function requireToBeLoggedIn (int $middlewareResponseType = -1): Closure {
      return function (Request $request, Response $response, Closure $next) use ($middlewareResponseType) {
        $isUserLoggedIn = $request->session->looselyGet("user") !== null;
        
        if ($isUserLoggedIn) {
          $next();
          return;
        }
        
        $responseType = $middlewareResponseType !== -1 ? $middlewareResponseType : self::$defaultResponseType;
  
        switch ($responseType) {
          case Middleware::RESPONSE_TEXT: {
            $response->render("error", ["message" => "You must log in first."]);
            break;
          }
          case Middleware::RESPONSE_JSON: {
            $response->json((object) ["error" => "You must login first."]);
            break;
          }
          case Middleware::RESPONSE_REDIRECT: {
            $response->redirect("/auth/");
            break;
          }
        }
      };
    }
  
    public static function requireToBeLoggedOut (int $middlewareResponseType = -1, string $redirectURL = "/dashboard"): Closure {
      return function (Request $request, Response $response, Closure $next) use ($middlewareResponseType, $redirectURL) {
        $isUserLoggedOut = $request->session->looselyGet("user") === null;
      
        if ($isUserLoggedOut) {
          $next();
          return;
        }
  
        $responseType = $middlewareResponseType !== -1 ? $middlewareResponseType : self::$defaultResponseType;
  
        switch ($responseType) {
          case Middleware::RESPONSE_TEXT: {
            //TODO: change to rendering an error view
            $response->render("error", ["message" => "You must not be logged in."]);
            break;
          }
          case Middleware::RESPONSE_JSON: {
            $response->json((object) ["error" => "You must not be logged in."]);
            break;
          }
          case Middleware::RESPONSE_REDIRECT: {
            $response->redirect($redirectURL);
            break;
          }
        }
      };
    }
    
    const LEVEL_ADMIN = 0;
    const LEVEL_USER = 1;
    
    public static function authorize ($requiredLevel, int $middlewareResponseType = -1, array $redirectMap = []): Closure {
      return function (Request $request, Response $response, Closure $next) use ($requiredLevel, $middlewareResponseType, $redirectMap) {
        /** @var User $user */
        $user = $request->session->get("user");
        $isUserAuthorized = $user->level == $requiredLevel;
        
        if ($isUserAuthorized) {
          $next();
          return;
        }
  
        $responseType = $middlewareResponseType !== -1 ? $middlewareResponseType : self::$defaultResponseType;
  
        switch ($responseType) {
          case Middleware::RESPONSE_TEXT: {
            $response->render("error", ["message" => "You don't have the permission to access this website."]);
            break;
          }
          case Middleware::RESPONSE_JSON: {
            $response->json((object) ["error" => "You don't have the permission to access this website."]);
            break;
          }
          case Middleware::RESPONSE_REDIRECT: {
            $response->redirect($redirectMap[$user->level] ?? "/");
            break;
          }
        }
      };
    }
  }