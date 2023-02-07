// const url = document.currentScript.getAttribute("url");
// window.addEventListener("load", async () => {
//   const theme = await AJAX.get(url ?? "/theme/user", JSONHandler(), __, AJAX.SERVER_HOME);
//   console.log(theme)
//   if (theme.error !== undefined) {
//     console.log(theme);
//     return;
//   }
//
//   const themeStyleElement = Component("style", "themes-link", String(theme.styles));
//   sessionStorage.setItem("themesSRC", theme.src);
//   window.dispatchEvent(new CustomEvent("themesLoaded"));
//   document.head.appendChild(themeStyleElement);
//
//   setTimeout(() => {
//     if (window.assignColors === undefined) return;
//     assignColors();
//   }, 10);
// });
//
//

/**
 * @typedef ThemeCoreType
 * @property {string} src
 * @property {string} styles
 * @property {Map<string, string>} variables
 */
/**
 * @typedef ThemeResponse
 * @property error
 * @property reason
 */
/**
 * @typedef ThemeAddon
 * @property {string} name
 * @property {number} usersID
 */
/**
 * @typedef {ThemeCoreType & ThemeAddon} ThemeType
 */
class Theme {
  /**
   * @type {Promise<ThemeCoreType>}
   */
  static #usersTheme;
  /**
   * @type {Promise<ThemeType[]>}
   */
  static #usersThemes;
  
  /**
   * @param url
   * @return {Promise<ThemeCoreType>}
   */
  static async get (url) {
    if (this.#usersTheme !== undefined) return this.#usersTheme;
  
    this.#usersTheme = AJAX.get(url, JSONHandler(), __, AJAX.SERVER_HOME)
      .catch(reason => ({error: "JSON parse error or network error", reason}));
    
    /**
     * @type {ThemeCoreType | ThemeResponse}
     */
    const theme = await this.#usersTheme;
    
    if (theme.error !== undefined) {
      this.#usersTheme = undefined;
      return Promise.reject(theme);
    }
  
    document.head.appendChild(
      Component("style", "themes-link", String(theme.styles))
    );
    
    this.#usersTheme = Promise.resolve(this.parse(theme));
    return this.#usersTheme;
  }
  
  /**
   * @param {string} themeSRC
   * @returns {Promise<void>}
   */
  static async setAsLink (themeSRC) {
    const themeLink = $(".themes-link");
    const newThemeLink = Component("link", "theme-link", __, {
      attributes: {
        id: "themes-link",
        rel: "stylesheet",
        href: AJAX.SERVER_HOME + "/theme/" + themeSRC
      }
    });
  
    document.head.appendChild(newThemeLink);
    
    if (themeLink === null) return Promise.resolve();
    
    return new Promise(resolve => {
      newThemeLink.addEventListener("load", async () => {
        await sleep(50);
        themeLink.remove();
        resolve();
      });
    });
  }
  
  /**
   * @param {*} base
   * @returns {ThemeType}
   */
  static parse (base) {
    if (base.styles === undefined) {
      base.variables = new Map();
      return base;
    }
    const variablesMap = new Map();
    for (const line of base.styles.split(/\r\n|\n/)) {
      const matches = /^\s*--([0-9a-zA-Z-_]+):\s*(.+);/.exec(line);
      if (matches === null) continue;
      variablesMap.set(matches[1], matches[2]);
    }
  
    base.variables = variablesMap;
    return base;
  }
  
  /**
   * @param {string} url
   * @return {Promise<ThemeType[]>}
   */
  static async getUsers (url) {
    if (this.#usersThemes !== undefined) return this.#usersThemes;
    
    this.#usersThemes = await AJAX.get(url, JSONHandler())
      .catch(reason => ({error: "JSON parse error or network error", reason}));
  
    /**
     * @type {ThemeCoreType | ThemeResponse}
     */
    const themes = await this.#usersThemes;
    
    if (themes.error !== undefined) {
      this.#usersThemes = undefined;
      return Promise.reject(themes);
    }
    
    this.#usersThemes = Promise.resolve(themes.map(this.parse));
    return this.#usersThemes;
  }
  
  /**
   * @param {ThemeType} theme
   * @param {HTMLElement} themeSelect
   * @returns {HTMLElement}
   */
  static createColor (theme, themeSelect) {
    return (
      Div("theme-colors", [
        Paragraph(__, theme.name)
      ], {
        attributes: {
          style: `
            --bg-0: ${theme.variables.get("container-0") ?? "#000"};
            --bg-1: ${theme.variables.get("container-opposite-3") ?? "#000"};
            --color-0: ${theme.variables.get("text-color-0") ?? "#000"};
            --color-1: ${theme.variables.get("text-color-opposite-3") ?? "#000"}
          `,
          "data-value": theme.src
        },
        listeners: {
          click: evt => {
            evt.stopImmediatePropagation();
            themeSelect.classList.remove("expand");
        
            if (themeSelect.dataset.value === theme.src) return;
        
            themeSelect.dataset.value = theme.src;
            themeSelect.dispatchEvent(new Event("change"));
          }
        }
      })
    )
  }
}