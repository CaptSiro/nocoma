window.addEventListener("load", async () => {
  document.body.classList.add("background-blend");
  document.body.style.backgroundImage = `url(${
    await AJAX.get("/auth/background", TextHandler(), {}, AJAX.SERVER_HOME)
  }?width=${window.innerWidth}&height=${window.innerHeight}&cropAndScale=true)`;
});