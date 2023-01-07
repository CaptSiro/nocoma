class WLink extends Widget {

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef LinkJSONType
   * @prop {string} label
   * @prop {string} url
   * @prop {string=} title
   * 
   * @typedef {LinkJSONType & WidgetJSON} LinkJSON
   */

  /**
   * @param {HTMLElement} root
   * @param {Widget} parent
   */
  constructor (root, parent) {
    super(root, parent);
    this.childSupport = "none";
  }

  /**
   * @override
   * @param {Widget} parent
   * @returns {WLink}
   */
  static default (parent) {
    return new WLink(Link("#", "w-link", "link", {
      attributes: {
        title: "link",
        target: "_blank"
      }
    }), parent);
  }

  /**
   * @override
   * @param {LinkJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WLink}
   */
  static build (json, parent, editable = false) {
    return new WLink(Link(json.url, "w-link", String(json.label ?? json.title ?? json.url), {
      attributes: {
        target: "_blank",
        title: json.title ?? ""
      }
    }), parent);
  }

  /**
   * @override
   * @returns {InspectorJSON}
   */
  get inspectorJSON () {
    return {
      elements: [{
        type: "Label",
        content: "Link"
      }]
    };
  }

  /**
   * @override
   * @returns {WidgetJSON}
   */
  save () {
    return {
      type: "WLink"
    };
  }
}
widgets.define("WLink", WLink);