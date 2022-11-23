var WRoot = class WRoot { // var is used because it creates reference on globalThis (window) object
  /**
   * @typedef RootJSONType
   * @prop {WidgetJSON[]} children
   * 
   * @typedef {RootJSONType & WidgetJSON} RootJSON
   */
  
  /**
   * @param {RootJSON} json
   * @param {"build"|"edit"} childFunction
   * @returns {HTMLElement}
   */
  static async #createRoot (json, childFunction = "build") {
    const script = document.createElement("script");
    const set = this.#walkWStructure(json, new Set());
    set.delete("WRoot");
    script.defer = true;

    let imports = '';
    for (const cls of set) {
      imports += cls + ",";
    }
    imports = imports.substring(0, imports.length - 1);
    script.src = AJAX.GET_DIR + "/bundler.php?w=" + imports;


    const css = document.createElement('link');
    css.rel = "stylesheet";
    css.type = "text/css";
    css.href = AJAX.GET_DIR + "/bundler.php?w=WRoot," + imports + "&ftype=css";
    document.head.appendChild(css);
    document.head.appendChild(script);


    const root = html({className: "w-root"});


    await Promise.all([new Promise(resolve => {
      script.addEventListener("load", async _ => {
        for (const child of json.children) {
          root.appendChild(window[child.type][childFunction](child));
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
   * @param {WidgetJSON} widget 
   * @param {Set} importSet 
   */
  static #walkWStructure (widget, importSet) {
    importSet.add(widget.type);

    if (widget.child) {
      importSet = this.#walkWStructure(widget.child, importSet);
    }

    if (widget.children) {
      for (const child of widget.children) {
        importSet = this.#walkWStructure(child, importSet);
      }
    }

    return importSet;
  }

  /**
   * @param {RootJSON} json
   * @returns {HTMLElement}
   */
  static async build (json) {
    return this.#createRoot(json);
  }

  /**
   * @param {RootJSON} json
   * @returns {HTMLElement}
   */
  static edit (json) {
    return this.#createRoot(json, "edit");
  }

  /**
   * @param {HTMLElement} element
   * @returns {JSON}
   */
  static destruct (element) {

  }
};