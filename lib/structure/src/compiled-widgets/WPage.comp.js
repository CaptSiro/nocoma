class WPage extends ContainerWidget {
  constructor (json, parent, editable = false) {
    super(
      Div("w-page" + (json.forceFullscreen ? " fullscreen" : "")),
      parent,
      editable
    );
    this.removeMargin();
    this.makeOutsideChildrenDragNotAllowed();
    this.childSupport = this.childSupport;
    if (json.forceFullscreen !== true) {
      this.rootElement.style.maxWidth = (json.width ?? 960) + "px";
    }
  }
  static default (parent, editable = false) {
    return new WPage({}, parent, editable);
  }
  static build (json, parent, editable = false) {
    return new WPage(json, parent, editable);
  }
  get inspectorHTML () {
    return (
      NotInspectorAble()
    )
  }
  save () {
    return {
      type: "WPage"
    };
  }
  remove () {
    console.error("WRoot cannot be removed.");
  }
}
widgets.define("WPage", WPage);
