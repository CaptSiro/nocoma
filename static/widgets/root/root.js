class WRoot extends ContainerWidget { // var is used because it creates reference on globalThis (window) object

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef Webpage
   * @property {number} ID
   * @property {boolean} areCommentsAvailable
   * @property {boolean} isHomePage
   * @property {boolean} isPublic
   * @property {boolean} isTakenDown
   * @property {boolean} isTemplate
   * @property {string} src
   * @property {string} thumbnailSRC
   * @property {string} timeCreated
   * @property {string} title
   * @property {number} usersID
   */
  
  /**
   * @param {WidgetJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   */
  constructor (json, parent, editable = false) {
    super(
      Div("w-root"),
      parent,
      false,
      true
    );
    this.editable = editable;
    
    this.page = WPage.build({}, this, editable);
    this.commentSection = WCommentSection.build({
      webpage: json.webpage
    }, this, editable);
    
    this.appendWidget(this.page);
    this.appendWidget(this.commentSection);
  }
  
  /**
   * @param {Set<string>} set
   * @returns {string}
   */
  static stringifyIterable (set) {
    let string = '';
    for (const cls of set) {
      string += cls + ",";
    }
    return string.substring(0, string.length - 1);
  }
  
  static #requestSet = new Set();
  
  /**
   * @param {...string} widgets
   */
  static requestWidgets (...widgets) {
    let query = '';
    for (const widget of widgets) {
      if (this.#requestSet.has(widget)) continue;
      
      this.#requestSet.add(widget);
      query += widget + ",";
    }
    
    if (query === "") return Promise.resolve();
    
    query = query.substring(0, query.length - 1);
    
    const css = document.createElement("link");
    css.rel = "stylesheet";
    css.type = "text/css";
    
    document.head.appendChild(css);
    const cssPromise = new Promise(resolve => {
      css.addEventListener("load", resolve);
    });
    css.href = AJAX.SERVER_HOME + "/bundler/css/" + query;
    
    const js = document.createElement("script");
    js.defer = true;
    
    document.head.appendChild(js);
    const jsPromise = new Promise(resolve => {
      js.addEventListener("load", resolve);
    });
    js.src = AJAX.SERVER_HOME + "/bundler/js/" + query;
    
    return Promise.all([cssPromise, jsPromise]);
  }

  /**
   * @param {WidgetJSON} json
   * @param {boolean} editable
   * @returns {Promise<WRoot>}
   */
  static async #createRoot (json, editable = false) {
    if (!editable) {
      await this.requestWidgets(...await this.#walkWStructure(json, new Set()))
    }
    
    const root = new WRoot(json, null, editable);
    
    for (const child of json.children) {
      await root.page.appendWidget(widgets.get(child.type).build(child, root.page, editable));
    }
    
    return root;
  }



  /**
   * @param {WidgetJSON} widget
   * @param {Set<string>} importSet
   * @param {boolean} bypassExistsRestriction
   */
  static async #walkWStructure (widget, importSet, bypassExistsRestriction = false) {
    if (!widgets.exists(widget.type) || bypassExistsRestriction) {
      importSet.add(widget.type);
    }

    if (widget.children) {
      for (const child of widget.children) {
        importSet = await this.#walkWStructure(child, importSet);
      }
    }

    return importSet;
  }



  /**
   * @override
   * @param {WidgetJSON} json
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
   * @param {boolean} editable
   * @returns {Widget}
   */
  static default (parent, editable = false) {
    return new WRoot({}, parent);
  }

  /**
   * @override
   * @returns {ComponentContent}
   */
  get inspectorHTML () {
    return (
      NotInspectorAble()
    )
  }

  /**
   * @override
   * @returns {WidgetJSON}
   */
  save () {
    return {
      type: "WRoot",
      children: this.page.saveChildren()
    };
  }

  /** @override */
  remove () {
    console.error("WRoot cannot be removed.");
  }
}
widgets.define("WRoot", WRoot);