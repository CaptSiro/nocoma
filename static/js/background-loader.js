window.addEventListener("load", () => {
  AJAX.get("/auth/background", TextHandlerSync((image) => {
    document.body.style.backgroundImage = `url(${AJAX.SERVER_HOME}/public/images/login-register-bgs/${image})`;
  }), {}, AJAX.SERVER_HOME);
});