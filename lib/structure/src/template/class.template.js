class W__class_name__ extends Widget {

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef __class_name__JSONType
   * @prop {string} text
   * 
   * @typedef {__class_name__JSONType & WidgetJSON} __class_name__JSON
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
   * @returns {W__class_name__}
   */
  static default (parent) {
    return new W__class_name__(Div(), parent);
  }

  /**
   * @override
   * @param {__class_name__JSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {W__class_name__}
   */
  static build (json, parent, editable = false) {
    return new W__class_name__(Div(), parent);
  }

  /**
   * @override
   * @returns {InspectorJSON}
   */
  get inspectorJSON () {
    return {
      elements: [{
        type: "Label",
        content: "__class_name__"
      }]
    };
  }

  /**
   * @override
   * @returns {WidgetJSON}
   */
  save () {
    return {
      type: "W__class_name__"
    };
  }
}
widgets.define("W__class_name__", W__class_name__);