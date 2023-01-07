class WHeading extends Widget { // var is used because it creates reference on globalThis (window) object

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef HeadingJSONType
   * @prop {string} text
   * @prop {number} level
   * 
   * @typedef {HeadingJSONType & WidgetJSON} HeadingJSON
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
   * @returns {WHeading}
   */
  static default (parent) {
    return this.build({ level: 3, text: "Lorem ipsum" }, parent);
  }

  /**
   * @override
   * @param {HeadingJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WHeading}
   */
  static build (json, parent, editable = false) {
    return new WHeading(
      Heading(
        Number(json.level ?? 3),
        "w-heading",
        json.text
      ), parent
    );
  }

  /**
   * @override
   * @returns {InspectorJSON}
   */
  get inspectorJSON () {
    return {
      elements: [{
        type: "Label",
        content: "Heading"
      }]
    };
  }

  /**
   * @override
   * @returns {WidgetJSON}
   */
  save () {
    return {
      type: "WHeading"
    };
  }
}
widgets.define("WHeading", WHeading);