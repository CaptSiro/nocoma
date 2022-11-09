var WRow = class WRow { // var is used because it creates reference on globalThis (window) object
  /**
   * @typedef RowJSONType
   * @prop {WidgetJSON[]} children
   * 
   * @typedef {RowJSONType & WidgetJSON} RowJSON
   */

  /**
   * @param {RowJSON} json
   * @returns {HTMLElement}
   */
  static build (json) {
    return html ({
      className: "w-row",
      content: json.children.map(o => {
        return window[o.type].build(o);
      })
    });
  }

  /**
   * @param {RowJSON} json
   * @returns {HTMLElement}
   */
  static edit (json) {

  }

  /**
   * @param {HTMLElement} element
   * @returns {JSON}
   */
  static destruct (element) {

  }
};