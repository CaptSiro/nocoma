class WColumn extends ContainerWidget { // var is used because it creates reference on globalThis (window) object

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef ColumnJSONType
   * @prop {{type: string}[]} children
   * 
   * @typedef {ColumnJSONType & WidgetJSON} ColumnJSON
   */

  /**
   * @param {HTMLElement} root
   * @param {Widget} parent
   */
  constructor (root, parent) {
    super(root, parent);
    this.childSupport = "multiple";
  }

  /**
   * @override
   * @param {Widget} parent
   * @returns {WColumn}
   */
  static default (parent) {
    return new WColumn(html({
      className: "w-column"
    }), parent);
  }

  /**
   * @override
   * @param {ColumnJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WColumn}
   */
  static build (json, parent, editable = false) {
    const col = new WColumn(html({
      className: "w-column"
    }), parent);

    for (const o of json.children) {
      col.appendWidget(widgets.get(o.type).build(o, col, editable));
    }

    return col;
  }

  /**
   * @override
   * @returns {InspectorJSON}
   */
  get inspectorJSON () {
    return {
      elements: [{
        type: "Label",
        content: "Column"
      }]
    };
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