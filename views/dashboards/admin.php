<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Nocoma</title>
  
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/main.js"></script>
  <script>
    AJAX.DOMAIN_HOME = "<?=$GLOBALS["__HOME__"]?>";
    AJAX.SERVER_HOME = "<?=$GLOBALS["SERVER_HOME"] ?? $GLOBALS["__HOME__"]?>";
  </script>
</head>
<body>
  Admin <button id="logout">Logout</button>
  <script>
    $("#logout").addEventListener("click", evt => {
      AJAX.delete("/auth/logout", new JSONHandler(json => {
        window.location.replace(json.redirect);
      }));
    });
  </script>
</body>
</html>