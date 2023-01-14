class WColumn extends ContainerWidget {
  constructor (json, parent, editable = false) {
    super(Div("w-column"), parent);
    this.childSupport = "multiple";
    for (const o of json.children) {
      this.appendWidget(widgets.get(o.type).build(o, this, editable));
    }
  }
  static default (parent, editable = false) {
    return new WColumn({
      children: []
    }, parent);
  }
  static build (json, parent, editable = false) {
    return new WColumn(json, parent);
  }
  get inspectorHTML () {
    return (
      NotInspectorAble()
    );
  }
  save () {
    return {
      type: "WColumn"
    };
  }
}
widgets.define("WColumn", WColumn);
