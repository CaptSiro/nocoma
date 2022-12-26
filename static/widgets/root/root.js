class WRoot extends ContainerWidget { // var is used because it creates reference on globalThis (window) object

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef RootJSONType
   * @prop {string} text
   * 
   * @typedef {RootJSONType & WidgetJSON} RootJSON
   */

  /**
   * @param {HTMLElement} root 
   * @param {Widget} parent 
   * @param {boolean} editable
   */
  constructor (root, parent, editable = false) {
    super(root, parent, editable);
    this.editable = editable;
  }

  /**
   * @param {RootJSON} json
   * @param {boolean} editable
   * @returns {Promise<WRoot>}
   */
  static async #createRoot (json, editable = false) {
    if (editable === true) {
      const root = new WRoot(html({className: "w-root"}), null, editable);
      for (const child of json.children) {
        root.appendWidget(widgets.get(child.type).build(child, root, editable));
      }
      return root;
    }

    const script = document.createElement("script");
    const set = this.#walkWStructure(json, new Set());
    set.delete("WRoot");
    script.defer = true;

    let imports = '';
    for (const cls of set) {
      imports += cls + ",";
    }
    imports = imports.substring(0, imports.length - 1);
    script.src = AJAX.SERVER_HOME + "/bundler/js/" + imports;


    const css = document.createElement('link');
    css.rel = "stylesheet";
    css.type = "text/css";
    css.href = AJAX.SERVER_HOME + "/bundler/css/WRoot," + imports;
    document.head.appendChild(script);
    document.head.appendChild(css);
    
    const root = new WRoot(html({className: "w-root"}), null, editable);
    
    await Promise.all([new Promise(resolve => {
      script.addEventListener("load", async _ => {
        for (const child of json.children) {
          root.appendWidget(widgets.get(child.type).build(child, root, editable));
        }
        resolve();
      });
    }), new Promise((resolve) => {
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
   * @override
   * @param {RootJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {Promise<Widget>}
   */
  static async build (json, parent = null, editable = false) {
    return await this.#createRoot(json, editable);
  }

  /**
   * @override
   * @param {Widget} parent
   * @returns {Widget}
   */
  static default (parent) {
    new WRoot(document.createElement("div"), parent);
  }

  /**
   * @override
   * @returns {InspectorJSON}
   */
  get inspectorJSON () {
    return {
      elements: [{
        type: "Label",
        content: "#Root"
      }]
    };
  }

  /**
   * @override
   * @returns {RootJSON}
   */
  save () {
    return {
      type: "WRoot",
      children: this.saveChildren()
    };
  }

  /** @override */
  remove () {
    console.error("WRoot cannot be removed.");
  }
}
widgets.define("WRoot", WRoot);