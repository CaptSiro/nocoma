class WColumn extends ContainerWidget { // var is used because it creates reference on globalThis (window) object

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef ColumnJSONType
   * @property {WidgetJSON[]} children
   * 
   * @typedef {ColumnJSONType & WidgetJSON} ColumnJSON
   */
  
  /**
   * @param {ColumnJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   */
  constructor (json, parent, editable = false) {
    super(Div("w-column"), parent);
    this.childSupport = "multiple";
  
    for (const o of json.children) {
      this.appendWidget(widgets.get(o.type).build(o, this, editable));
    }
  }

  /**
   * @override
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WColumn}
   */
  static default (parent, editable = false) {
    return new WColumn({
      children: []
    }, parent);
  }

  /**
   * @override
   * @param {ColumnJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WColumn}
   */
  static build (json, parent, editable = false) {
    return new WColumn(json, parent);
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
      type: "WColumn"
    };
  }
}
widgets.define("WColumn", WColumn);