class WCode extends Widget {
  #textEditor;
  constructor (json, parent, editable = false) {
    super(Component("code", "w-code"), parent, editable);
    this.childSupport = 1;
    this.#textEditor = WTextEditor.build(json.textEditor, this, editable);
    this.appendWidget(this.#textEditor);
    if (editable !== true) {
      return;
    }
    this.appendEditGui();
    this.rootElement.classList.add("edit");
  }
  static default (parent, editable = false) {
    return new WCode({
      textEditor: {
        content: [],
        mode: "simple"
      }
    }, parent, editable);
  }
  static build (json, parent, editable = false) {
    return new WCode(json, parent, editable);
  }
  get inspectorHTML () {
    return (
      NotInspectorAble()
    );
  }
  save () {
    return {
      type: "WCode",
      textEditor: this.#textEditor.save()
    };
  }
}
widgets.define("WCode", WCode);
