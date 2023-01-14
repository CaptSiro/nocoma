class WRoot extends ContainerWidget {
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
  static stringifySet (set) {
    let string = '';
    for (const cls of set) {
      string += cls + ",";
    }
    return string.substring(0, string.length - 1);
  }
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
