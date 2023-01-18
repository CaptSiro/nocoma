class WRoot extends ContainerWidget {
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
  static async build (json, parent = null, editable = false) {
    return await this.#createRoot(json, editable);
  }
  static default (parent, editable = false) {
    return new WRoot({}, parent);
  }
  get inspectorHTML () {
    return (
      NotInspectorAble()
    )
  }
  save () {
    return {
      type: "WRoot",
      children: this.page.saveChildren()
    };
  }
  remove () {
    console.error("WRoot cannot be removed.");
  }
}
WRoot.addToRequestSet("WRoot", "WPage", "WCommentSection", "WTextEditor", "WTextDecoration", "WComment")
widgets.define("WRoot", WRoot);
