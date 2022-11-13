var WRichText = class WRichText { // var is used because it creates reference on globalThis (window) object
  /**
   * @typedef RichTextJSONType
   * @prop {string} text
   * 
   * @typedef {JSONType & WidgetJSON} RichTextJSON
   */

  /**
   * @param {RichTextJSON} json
   * @returns {HTMLElement}
   */
  static build (json) {
    return html ({
      
    });
  }

  /**
   * @param {RichTextJSON} json
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