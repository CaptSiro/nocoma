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
  
  static #called = false;
  static addToRequestSet (...classes) {
    if (WRoot.#called) return;
    
    WRoot.#called = true;
  
    for (const c of classes) {
      WRoot.#requestSet.add(c);
    }
  }
  
  /**
   * @param {...string} widgetsArray
   */
  static requestWidgets (...widgetsArray) {
    let query = '';
    
    let subtrahend = "";
    if (this.#requestSet.size !== 0) {
      subtrahend = "/" + this.stringifyIterable(this.#requestSet);
    }
    
    let widgetPromises = [];
    for (const widgetClass of widgetsArray) {
      if (this.#requestSet.has(widgetClass)) continue;
      console.log(widgetClass);
      
      this.#requestSet.add(widgetClass);
      widgetPromises.push(
        new Promise(resolve => widgets.on(widgetClass, resolve))
      );
      query += widgetClass + ",";
    }
    
    if (query === "") return Promise.resolve();
    
    query = query.substring(0, query.length - 1) + subtrahend;
    
    const cssID = guid(true);
    const css = document.createElement("link");
    css.rel = "stylesheet";
    css.type = "text/css";
    css.id = cssID;
    
    document.head.appendChild(css);
    const cssPromise = untilElement("#" + cssID);
    cssPromise.then(() => freeID(cssID));
    css.href = AJAX.SERVER_HOME + "/bundler/css/" + query;
    
    const jsID = guid(true);
    const js = document.createElement("script");
    js.defer = true;
    
    document.head.appendChild(js);
    const jsPromise = untilElement("#" + jsID);
    jsPromise.then(() => freeID(jsID))
    js.src = AJAX.SERVER_HOME + "/bundler/js/" + query;
  
    console.log([cssPromise, jsPromise, ...widgetPromises]);
    return Promise.all([cssPromise, jsPromise, ...widgetPromises]);
  }

  /**
   * @param {WidgetJSON} json
   * @param {boolean} editable
   * @returns {Promise<WRoot>}
   */
  static async #createRoot (json, editable = false) {
    if (!editable) {
      await widgets.request(...await this.walkWStructure(json, new Set()))
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
  static async walkWStructure (widget, importSet, bypassExistsRestriction = false) {
    if (!widgets.exists(widget.type) || bypassExistsRestriction) {
      importSet.add(widget.type);
    }

    if (widget.children) {
      for (const child of widget.children) {
        importSet = await this.walkWStructure(child, importSet);
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
WRoot.addToRequestSet("WRoot", "WPage", "WCommentSection", "WTextEditor", "WTextDecoration", "WComment")
widgets.define("WRoot", WRoot);