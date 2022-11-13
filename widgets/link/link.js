var WLink = class WLink { // var is used because it creates reference on globalThis (window) object
  /**
   * @typedef LinkJSONType
   * @prop {string} label
   * @prop {string} url
   * @prop {string=} title
   * 
   * @typedef {LinkJSONType & WidgetJSON} LinkJSON
   */

  /**
   * @param {LinkJSON} json
   * @returns {HTMLElement}
   */
  static build (json) {
    return html ({
      name: "a",
      content: json.label ?? json.title ?? json.url,
      attributes: {
        href: json.url,
        title: json.title ?? "",
        target: "_blank"
      }
    });
  }

  

  /**
   * @param {LinkJSON} json
   * @returns {HTMLElement}
   */
  static edit (json) {
    return html ({
      name: "a",
      content: json.label ?? json.title ?? json.url,
      attributes: {
        href: json.url,
        title: json.title ?? "",
        target: "_blank"
      }
    });
  }

  /**
   * @param {HTMLElement} element
   * @returns {JSON}
   */
  static destruct (element) {

  }
};