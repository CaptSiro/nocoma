class WHeading extends Widget {
  constructor (json, parent, editable = false) {
    super(
      Heading(
        Number(json.level ?? 3),
        "w-heading"
      ),
      parent
    );
    this.childSupport = 1;
    this.appendWidget(WTextEditor.build({
      content: [json.text],
      forceSingleLine: true,
      mode: "simple"
    }, this, editable));
    if (editable) {
      this.appendEditGui();
    }
  }
  static default (parent, editable = false) {
    return this.build({ level: 3, text: "Lorem ipsum" }, parent, editable);
  }
  static build (json, parent, editable = false) {
    return new WHeading(json, parent, editable);
  }
  get inspectorHTML () {
    return (
      NotInspectorAble()
    );
  }
  save () {
    return {
      type: "WHeading",
      level: Number(this.rootElement.tagName[1]),
      text: this.children[0].rootElement.textContent
    };
  }
}
widgets.define("WHeading", WHeading);
