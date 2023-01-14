class WText extends Widget {
  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef TextJSONType
   * @property {TextEditorJSON} textEditor
   *
   * @typedef {TextJSONType & WidgetJSON} TextJSON
   */
  
  
  #textEditor;
  
  /**
   * @param {TextJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   */
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

  /**
   * @override
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WText}
   */
  static default (parent, editable) {
    return WText.build({ textEditor: { content: [], mode: "fancy" } }, parent, editable);
  }

  /**
   * @override
   * @param {TextJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WText}
   */
  static build (json, parent, editable = false) {
    return new WText(json, parent, editable);
  }

  /**
   * @override
   * @returns {ComponentContent}
   */
  get inspectorHTML () {
    return (
      TitleInspector("Text")
    )
  }

  /**
   * @override
   * @returns {TextJSON}
   */
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