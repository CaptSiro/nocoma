window.addEventListener("load", evt => {
  AJAX.get("/auth/background", new JSONHandler((images) => {
    document.body.style.backgroundImage = `url(${AJAX.HOME}/public/images/login-register-bgs/${images[flatRNG(0, images.length - 1)]})`;
  }));
});