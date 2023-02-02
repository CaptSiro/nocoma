const url = document.currentScript.getAttribute("url");
window.addEventListener("load", async () => {
  const theme = await AJAX.get(url ?? "/theme/user", JSONHandler(), __, AJAX.SERVER_HOME);
  
  if (theme.error !== undefined) {
    console.log(theme);
    return;
  }
  
  const themeStyleElement = Component("style", "themes-link", String(theme.styles));
  sessionStorage.setItem("themesSRC", theme.src);
  window.dispatchEvent(new CustomEvent("themesLoaded"));
  document.head.appendChild(themeStyleElement);
  
  setTimeout(() => {
    if (window.assignColors === undefined) return;
    assignColors();
  }, 10);
});