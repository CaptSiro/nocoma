var WHeading = class WHeading { // var is used because it creates reference on globalThis (window) object
  /**
   * @typedef HeadingJSONType
   * @prop {string} text
   * @prop {number} level
   * 
   * @typedef {HeadingJSONType & WidgetJSON} HeadingJSON
   */

  /**
   * @param {HeadingJSON} json
   * @returns {HTMLElement}
   */
  static build (json) {
    return html ({
      name: "h" + Math.min(Math.max(0, json.level), 6),
      content: json.text,
      className: "w-heading"
    });
  }

  /**
   * @param {HeadingJSON} json
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