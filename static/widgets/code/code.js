class WCode extends Widget {

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef CodeJSONType
   * @property {TextEditorJSON} textEditor
   * 
   * @typedef {CodeJSONType & WidgetJSON} CodeJSON
   */
  
  #textEditor;

  /**
   * @param {CodeJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   */
  constructor (json, parent, editable = false) {
    super(Component("code", "w-code"), parent);
    this.childSupport = 1;
    
    this.#textEditor = WTextEditor.build(json.textEditor, this, editable);
    this.appendWidget(this.#textEditor);
    
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
   * @returns {WCode}
   */
  static default (parent, editable = false) {
    return new WCode({
      textEditor: {
        content: [],
        mode: "simple"
      }
    }, parent, editable);
  }

  /**
   * @override
   * @param {CodeJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WCode}
   */
  static build (json, parent, editable = false) {
    return new WCode(json, parent, editable);
  }

  /**
   * @override
   * @returns {ComponentContent}
   */
  get inspectorHTML () {
    return (
      NotInspectorAble()
    );
  }

  /**
   * @override
   * @returns {WidgetJSON}
   */
  save () {
    return {
      type: "WCode",
      textEditor: this.#textEditor.save()
    };
  }
}
widgets.define("WCode", WCode);