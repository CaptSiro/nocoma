window.addEventListener("load", () => {
  AJAX.get("/auth/background", new TextHandler((image) => {
    document.body.style.backgroundImage = `url(${AJAX.SERVER_HOME}/public/images/login-register-bgs/${image})`;
  }), {}, AJAX.SERVER_HOME);
});