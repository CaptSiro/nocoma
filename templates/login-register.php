<?php
  require_once __DIR__ . "/../models/User.php";
  require_once __DIR__ . "/../lib/rekves/rekves.php";
  require_once __DIR__ . "/../lib/authenticate.php";


  Auth::redirect($req->session->get("user"), [AUTH_NOT_LOGGED_IN], AUTH_DEFAULT_REDIRECT_MAP);
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - NoComa</title>

  <link rel="stylesheet" href="../public/css/main.css">
  <link rel="stylesheet" href="../public/css/login-register.css">

  <script src="../public/js/main.js"></script>

  <script src="../public/js/components/Timer.js"></script>
  <script src="../public/js/components/ChainedInputs.js"></script>
  <script src="../public/js/components/FireAble.js"></script>
  <script src="../public/js/components/SequentialAnimation.js"></script>
  <script src="../public/js/components/TextSlider.js"></script>

  <script src="../public/js/forms.js" defer></script>
  <script src="../public/js/login-register.js" defer></script>
</head>
<body class="center">
  <p class="PHP-Exception"></p>


  <div class="form login">
    <div class="wrapper">
      <p class="label">Email:</p>
      <input type="text" name="l-email" id="l-email">
    </div>

    <div class="wrapper">
      <p class="label">Password:</p>
      <input type="password" name="l-password" id="l-password">
    </div>
    
    <div class="wrapper">
      <button class="link" link-to="forgotten-password">Forgotten password?</button>
    </div>

    <div class="divider"></div>

    <div class="wrapper">
      <button class="submit">Login</button>
    </div>

    <div class="wrapper">
      <p class="blockquote error"></p>
    </div>

    <div class="hline"></div>

    <div class="wrapper">
      <p>Don't have an account? <button class="link" link-to="register">Sign up.</button></p>
    </div>
  </div>


  <div class="form forgotten-password hide">
    <div class="wrapper">
      <p class="label">Please enter your email you used for registration:</p>
      <input type="text" name="f-email" id="f-email" regex="email">
      <p class="blockquote error"></p>
    </div>

    <div class="wrapper">
      <p class="blockquote note">Note: You may request password only on verified accounts.</p>
    </div>

    <div class="divider"></div>

    <div class="wrapper">
      <button class="submit">Submit</button>
      <p class="blockquote error"></p>
    </div>

    <div class="hline"></div>

    <div class="wrapper">
      <p>Back to <button class="link" link-to="login">login.</button></p>
    </div>
  </div>


  <div class="form forgotten-password-2 hide">
    <div class="wrapper">
      <p class="label">We have sent you email with instruction. Please follow from there. (You may close this tab)</p>
    </div>

    <div class="divider"></div>

    <div class="wrapper">
      <p>Back to <button class="link" link-to="login">login.</button></p>
    </div>
  </div>


  <div class="form register hide">
    <div class="wrapper">
      <p class="label">Email:</p>
      <input type="text" name="r-email" id="r-email" regex="email">
      <p class="blockquote error"></p>
    </div>

    <div class="wrapper">
      <p class="label">Website:</p>
      <span class="url-website">./hosts/<span id="your-website"></span></span>
      <input type="text" name="r-website" id="r-website" regex="website">
      <p class="blockquote error"></p>
    </div>

    <div class="wrapper">
      <p class="label">Password:</p>
      <input type="password" name="r-password" id="r-password" regex="password">
      <p class="blockquote error"></p>
    </div>

    <div class="wrapper">
      <p class="label">Password again:</p>
      <input type="password" name="r-password-again" id="r-password-again">
      <p class="blockquote error"></p>
    </div>

    <div class="wrapper">
      <label class="checkbox-container">
        <input type="checkbox" name="checkbox" id="r-tos-pp">
        <span>I agree to the <a href="./terms-of-service.html">terms of service</a> and <a href="./privacy-policy.html">privacy policy</a></span>
      </label>
    </div>

    <div class="divider"></div>

    <div class="wrapper">
      <button class="submit">Register</button>
      <p class="blockquote error"></p>
    </div>

    <div class="hline"></div>

    <div class="wrapper">
      <p>Already have an account? <button class="link" link-to="login">Login.</button></p>
    </div>
  </div>


  <div class="form code-verification hide">
    <div class="wrapper">
      <p class="label">We have sent you 6-digit code on your email address to verify your account. Be sure to check spam as well.</p>
    </div>

    <div class="wrapper">
      <button class="link request-code">You may request code again in (4:59)</button>
    </div>

    <div class="divider"></div>

    <div class="wrapper">
      <div class="chained-inputs center input-code">
        <input type="number">
        <input type="number">
        <input type="number">
        <input type="number">
        <input type="number">
        <input type="number">
      </div>

      <script>
        const getVerificationValue = chainedInputs(document.currentScript.previousElementSibling);
      </script>

      <p class="blockquote error center-text"></p>
    </div>

    <div class="divider"></div>

    <div class="wrapper">
      <button class="submit">Submit code</button>
    </div>
  </div>
</body>
</html>