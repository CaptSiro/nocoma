var WRoot = class WRoot { // var is used because it creates reference on globalThis (window) object
  /**
   * @typedef RootJSONType
   * @prop {string[]} imports
   * @prop {WidgetJSON[]} children
   * 
   * @typedef {RootJSONType & WidgetJSON} RootJSON
   */

  /**
   * @param {RootJSON} json
   * @returns {HTMLElement}
   */
  static async build (json) {
    const script = document.createElement("script");
    script.defer = true;
    script.src = AJAX.GET_DIR + "/bundler.php?w=" + json.imports.join(",");


    const css = document.createElement('link');
    css.rel = "stylesheet";
    css.type = "text/css";
    css.href = AJAX.GET_DIR + "/bundler.php?w=" + json.imports.concat(["WRoot"]).join(",") + "&ftype=css";
    document.head.appendChild(css);
    document.head.appendChild(script);


    const root = html({className: "w-root"});


    await Promise.all([new Promise(resolve => {
      script.addEventListener("load", async _ => {
        for (const child of json.children) {
          root.appendChild(window[child.type]?.build(child));
        }
        resolve();
      });
    }), new Promise((resolve, reject) => {
      css.addEventListener("load", _ => {
        resolve();
      });
    })]);
    
    return root;
  }

  /**
   * @param {RootJSON} json
   * @returns {HTMLElement}
   */
  static edit (json) {
    return html ({
      className: "w-root",
      content: json.children.map(o => window[o.type].edit(o))
    });
  }

  /**
   * @param {HTMLElement} element
   * @returns {JSON}
   */
  static destruct (element) {

  }
};