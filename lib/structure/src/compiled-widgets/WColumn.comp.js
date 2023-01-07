class WColumn extends ContainerWidget {
  constructor (root, parent) {
    super(root, parent);
    this.childSupport = "multiple";
  }
  static default (parent) {
    return new WColumn(
      Div("w-column"),
      parent
    );
  }
  static build (json, parent, editable = false) {
    const col = new WColumn(
      Div("w-column"),
      parent
    );
    for (const o of json.children) {
      col.appendWidget(widgets.get(o.type).build(o, col, editable));
    }
    return col;
  }
  get inspectorJSON () {
    return {
      elements: [{
        type: "Label",
        content: "Column"
      }]
    };
  }
  save () {
    return {
      type: "WColumn"
    };
  }
}
widgets.define("WColumn", WColumn);
