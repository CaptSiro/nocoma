class WText extends Widget {
  #textEditor;
  constructor (json, parent, editable = false) {
    super(Paragraph("w-text"), parent, editable);
    this.#textEditor = WTextEditor.build(json.textEditor, this, editable);
    this.childSupport = 1;
    this.appendWidget(this.#textEditor);
    if (editable !== true) {
      return;
    }
    this.appendEditGui();
    this.rootElement.classList.add("edit");
  }
  static default (parent, editable) {
    return WText.build({
      textEditor: {
        content: [],
      }
    }, parent, editable);
  }
  static build (json, parent, editable = false) {
    return new WText(json, parent, editable);
  }
  get inspectorHTML () {
    return (
      NotInspectorAble()
    )
  }
  save () {
    return {
      type: "WText",
      textEditor: this.#textEditor.save()
    };
  }
  focus() {
    this.#textEditor.focus();
  }
}
widgets.define("WText", WText);
