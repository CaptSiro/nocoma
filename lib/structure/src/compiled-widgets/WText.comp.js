class WText extends Widget {
  #textEditor;
  constructor (json, parent, editable = false) {
    super(Paragraph("w-text"), parent);
    this.#textEditor = WTextEditor.build(json.textEditor, this, editable);
    this.#textEditor.setMode("fancy");
    this.#textEditor.setForceSingleLine(false);
    this.appendWidget(this.#textEditor);
    this.childSupport = 1;
    if (editable !== true) {
      return;
    }
    this.appendEditGui();
    this.rootElement.classList.add("edit");
  }
  static default (parent, editable) {
    return WText.build({ textEditor: { content: [], mode: "fancy" } }, parent, editable);
  }
  static build (json, parent, editable = false) {
    return new WText(json, parent, editable);
  }
  get inspectorHTML () {
    return (
      TitleInspector("Text")
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
