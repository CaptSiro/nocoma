<?php
  
  require_once __DIR__ . "/../lib/routepass/routers.php";
  
  require_once __DIR__ . "/Middleware.php";
  
  require_once __DIR__ . "/../models/Comment.php";
  
  $commentRouter = new Router();
  
  
  
  $commentRouter->options("/", [Middleware::corsAllowAll("POST")]);
  $commentRouter->post("/", [
    Middleware::requireToBeLoggedIn(),
    Middleware::corsAllowAll("POST", false),
    function (Request $request, Response $response) {
      $sideEffect = Comment::insert(
        intval($request->body->get("websitesID")),
        $request->session->get("user")->ID,
        $request->body->get("content"),
        $request->body->looselyGet("parentCommentID"),
      );
      
      if ($sideEffect->rowCount !== 1) {
        $response->error("Could not add comment.", Response::INTERNAL_SERVER_ERROR);
      }
      
      $response->json(Comment::getByID($sideEffect->lastInsertedID));
    }
  ]);
  
  
  
  $commentRouter->options("/:commentID", [Middleware::corsAllowAll("DELETE")], ["commentID" => Router::REGEX_NUMBER]);
  $isUserEligible = function (Request $request, Response $response, Closure $next) {
    $comment = Comment::getByID($request->param->get("commentID"));
    if ($comment === false) {
      $response->json(["error" => "404: Comment not found."]);
    }
  
    $isAdmin = $request->session->isset("user")
      && $request->session->get("user")->level === Middleware::LEVEL_ADMIN;
  
    if ($isAdmin) $next($comment);
  
    $isWebsiteCreator = $request->session->isset("user")
      && $request->session->get("user")->ID === $comment->creatorID;
  
    if ($isWebsiteCreator) $next($comment);
  
    $isCommentPoster = $request->session->isset("user")
      && $request->session->get("user")->ID === $comment->usersID;
  
    if ($isCommentPoster) $next($comment);
  };
  $commentRouter->delete("/:commentID", [
    Middleware::requireToBeLoggedIn(),
    Middleware::corsAllowAll("DELETE", false),
    $isUserEligible,
    function (Request $request, Response $response, Closure $next, Comment $comment) {
      $response->json(Comment::delete($comment->ID));
    }
  ], ["commentID" => Router::REGEX_NUMBER]);
  
  
  
  $commentRouter->options("/:websiteID/:offset", [Middleware::corsAllowAll("GET")], ["websiteID" => Router::REGEX_NUMBER, "offset" => Router::REGEX_NUMBER]);
  $defaultUser = new stdClass();
  $defaultUser->ID = 0;
  $commentRouter->get("/:websiteID/:offset", [
    Middleware::corsAllowAll("GET", false),
    function (Request $request, Response $response) use ($defaultUser) {
      $response->json(Comment::getSet(
        intval($request->param->get("websiteID")),
        intval($request->session->looselyGet("user", $defaultUser)->ID),
        intval($request->param->get("offset")),
      ));
    }
  ], ["websiteID" => Router::REGEX_NUMBER, "offset" => Router::REGEX_NUMBER]);
  
  
  $commentRouter->options("/:websiteID/replies/:commentsID/:offset", [Middleware::corsAllowAll("GET")], ["websiteID" => Router::REGEX_NUMBER, "commentsID" => Router::REGEX_NUMBER, "offset" => Router::REGEX_NUMBER]);
  $commentRouter->get("/:websiteID/replies/:commentsID/:offset", [
    Middleware::corsAllowAll("GET", false),
    function (Request $request, Response $response) use ($defaultUser) {
      $response->json(Comment::getSet(
        intval($request->param->get("websiteID")),
        intval($request->session->looselyGet("user", $defaultUser)->ID),
        intval($request->param->get("offset")),
        intval($request->param->get("commentsID")),
      ));
    }
  ], ["websiteID" => Router::REGEX_NUMBER, "commentsID" => Router::REGEX_NUMBER, "offset" => Router::REGEX_NUMBER]);
  
  
  
  $reactionLookUp = [
    "like" => 1,
    "none" => 0,
    "dislike" => -1
  ];
  $commentRouter->options("/react/:commentID/:reaction", [Middleware::corsAllowAll("PATCH")], [
    "commentID" => Router::REGEX_NUMBER,
    "reaction" => Router::REGEX_ENUM(array_keys($reactionLookUp))
  ]);
  $commentRouter->patch("/react/:commentID/:reaction", [
    Middleware::requireToBeLoggedIn(),
    Middleware::corsAllowAll("PATCH", false),
    function (Request $request, Response $response) use ($reactionLookUp) {
      $response->json(Comment::setReaction(
        $request->param->get("commentID"),
        $request->session->get("user")->ID,
        $reactionLookUp[$request->param->get("reaction")]
      ));
    }
  ], [
    "commentsID" => Router::REGEX_NUMBER,
    "reaction" => Router::REGEX_ENUM(array_keys($reactionLookUp))
  ]);
  
  
  
  $isPinnedStates = ["pin", "unpin"];
  $commentRouter->options("/is-pinned/:commentID/:pinState", [
    Middleware::corsAllowAll("PUT")
  ], ["commentID" => Router::REGEX_NUMBER, "pinState" => Router::REGEX_ENUM($isPinnedStates)]);
  $commentRouter->put("/is-pinned/:commentID/:pinState", [
    Middleware::corsAllowAll("PUT", false),
    Middleware::requireToBeLoggedIn(),
    $isUserEligible,
    function (Request $request, Response $response) {
      /**
       * @type User $user
       */
      $user = $request->session->get("user");
      
      // todo check if user can pin (admin or creator)
    
      $response->json(Comment::setIsPinned(
        intval($request->param->get("commentID")),
        $request->param->get("pinState") === "pin"
      ));
    }
  ], ["commentID" => Router::REGEX_NUMBER, "pinState" => Router::REGEX_ENUM($isPinnedStates)]);
  
  
  
  $commentRouter->options("/count/:websiteID", [Middleware::corsAllowAll("GET")], ["websiteID" => Router::REGEX_NUMBER]);
  $commentRouter->get("/count/:websiteID", [
    Middleware::corsAllowAll("GET", false),
    function (Request $request, Response $response) {
      $response->send(Comment::countTotal($request->param->get("websiteID")));
    }
  ], ["websiteID" => Router::REGEX_NUMBER]);
  
  
  
  return $commentRouter;