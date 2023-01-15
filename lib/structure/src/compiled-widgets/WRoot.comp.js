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
widgets.define("WRoot", WRoot);
