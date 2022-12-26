class WRow extends ContainerWidget {
  constructor (root, parent) {
    super(root, parent);
    this.childSupport = this.childSupport;
  }
  static default (parent) {
    return this.build({ children: [] }, parent, true);
  }
  static build (json, parent, editable = false) {
    const row = new WRow(html({
      className: "w-row"
    }), parent);
    for (const o of json.children) {
      row.appendWidget(widgets.get(o.type).build(o, row, editable));
    }
    return row;
  }
  get inspectorJSON () {
    return {
      elements: [{
        type: "Label",
        content: "Row"
      }]
    };
  }
  save () {
    return {
      type: "WRow"
    };
  }
}
widgets.define("WRow", WRow);
