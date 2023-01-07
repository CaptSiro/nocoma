class WArticle extends ContainerWidget {
  constructor (root, parent) {
    super(root, parent);
    this.childSupport = this.childSupport;
  }
  static default (parent) {
    return new WArticle(Div(), parent);
  }
  static build (json, parent, editable = false) {
    return new WArticle(Div(), parent);
  }
  get inspectorJSON () {
    return {
      elements: [{
        type: "Label",
        content: "Article"
      }]
    };
  }
  save () {
    return {
      type: "WArticle"
    };
  }
}
widgets.define("WArticle", WArticle);
