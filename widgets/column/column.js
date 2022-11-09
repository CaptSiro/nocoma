var WColumn = class WColumn { // var is used because it creates reference on globalThis (window) object
  /**
   * @typedef ColumnJSONType
   * @prop {{type: string}[]} children
   * 
   * @typedef {ColumnJSONType & WidgetJSON} ColumnJSON
   */

  /**
   * @param {ColumnJSON} json
   * @returns {HTMLElement}
   */
  static build (json) {
    return html ({
      className: "w-column",
      content: json.children.map(c => window[c.type].build(c))
    });
  }

  /**
   * @param {ColumnJSON} json
   * @returns {HTMLElement}
   */
  static edit (json) {
    return html ({
      className: "w-column",
      content: json.children.map(c => window[c.type].edit(c))
    });
  }

  /**
   * @param {HTMLElement} element
   * @returns {ColumnJSON}
   */
  static destruct (element) {

  }
};