class WComment extends Widget {
  constructor (json, parent, editable = false) {
    super(Div(), parent);
    this.childSupport = this.childSupport;
  }
  static default (parent, editable = false) {
    return new WComment({}, parent, editable);
  }
  static build (json, parent, editable = false) {
    return new WComment({}, parent, editable);
  }
  get inspectorHTML () {
    return (
      NotInspectorAble()
    );
  }
  save () {
    return {
      type: "WComment"
    };
  }
}
widgets.define("WComment", WComment);
