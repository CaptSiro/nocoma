class WRow extends ContainerWidget { // var is used because it creates reference on globalThis (window) object

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef RowJSONType
   * @property {WidgetJSON[]} children
   * 
   * @typedef {RowJSONType & WidgetJSON} RowJSON
   */

  /**
   * @param {HTMLElement} root
   * @param {Widget} parent
   */
  constructor (root, parent) {
    super(root, parent);
    this.childSupport = this.childSupport;
  }

  /**
   * @override
   * @param {Widget} parent
   * @returns {WRow}
   */
  static default (parent) {
    return this.build({ children: [] }, parent, true);
  }

  /**
   * @override
   * @param {RowJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WRow}
   */
  static build (json, parent, editable = false) {
    const row = new WRow(Div("w-row"), parent);

    for (const o of json.children) {
      row.appendWidget(widgets.get(o.type).build(o, row, editable));
    }

    return row;
  }

  /**
   * @override
   * @returns {ComponentContent}
   */
  get inspectorHTML () {
    return (
      TitleInspector("Row")
    );
  }

  /**
   * @override
   * @returns {WidgetJSON}
   */
  save () {
    return {
      type: "WRow"
    };
  }
}
widgets.define("WRow", WRow);