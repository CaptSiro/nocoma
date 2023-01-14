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
   * @typedef RootJSONType
   * @property {Webpage} webpage
   *
   * @typedef {RootJSONType & WidgetJSON} RootJSON
   */
  
  /**
   * @param {__class_name__JSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   */
  constructor (json, parent, editable = false) {
    super(
      Div("w-root no-margin"),
      parent,
      editable
    );
    this.editable = editable;
    
    this.page = WPage.build({}, this, editable);
    this.commentSection = WCommentSection.build({}, this, editable);
    
    this.appendWidget(this.page);
    this.appendWidget(this.commentSection);
  }
  
  /**
   * @param {Set<string>} set
   * @returns {string}
   */
  static stringifySet (set) {
    let string = '';
    for (const cls of set) {
      string += cls + ",";
    }
    return string.substring(0, string.length - 1);
  }
  
  /**
   * @param {RootJSON} json
   * @returns {Promise<Event>}
   */
  static #loadStyles (json) {
    return new Promise(resolve => {
      const css = document.createElement('link');
      this.#walkWStructure(json, new Set(), true)
        .then(set => {
          css.href = AJAX.SERVER_HOME + "/bundler/css/" + this.stringifySet(set);
        })
      
      css.rel = "stylesheet";
      css.type = "text/css";
      
      document.head.appendChild(css);
      
      css.addEventListener("load", resolve);
    });
  }
  
  /**
   * @param {RootJSON} json
   * @returns {Promise<Event>}
   */
  static #loadScripts (json) {
    return new Promise(resolve => {
      const script = document.createElement("script");
      this.#walkWStructure(json, new Set())
        .then(set => {
          script.src = AJAX.SERVER_HOME + "/bundler/js/" + this.stringifySet(set);
        });
      
      script.defer = true;
      document.head.appendChild(script);
      
      script.addEventListener("load", resolve);
    });
  }

  /**
   * @param {RootJSON} json
   * @param {boolean} editable
   * @returns {Promise<WRoot>}
   */
  static async #createRoot (json, editable = false) {
    const widgetCodeFiles = Promise.all(
      editable
        ? [Promise.resolve()]
        : [this.#loadScripts(json), this.#loadStyles(json)]
    );
    
    const root = new WRoot(json, null, editable);
    
    await widgetCodeFiles;
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
   * @returns {RootJSON}
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