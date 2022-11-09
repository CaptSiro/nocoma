var W = class W { // var is used because it creates reference on globalThis (window) object
  /**
   * @typedef JSONType
   * @prop {string} text
   * 
   * @typedef {JSONType & WidgetJSON} JSON
   */

  /**
   * @param {JSON} json
   * @returns {HTMLElement}
   */
  static build (json) {
    return html ({
      
    });
  }

  /**
   * @param {JSON} json
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