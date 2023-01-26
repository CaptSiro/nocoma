class W__class_name__ extends Widget {

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef __class_name__JSONType
   * @property {string=} text
   * 
   * @typedef {__class_name__JSONType & WidgetJSON} __class_name__JSON
   */

  /**
   * @param {__class_name__JSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   */
  constructor (json, parent, editable = false) {
    super(Div(), parent);
    this.childSupport = this.childSupport;
  }

  /**
   * @override
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {W__class_name__}
   */
  static default (parent, editable = false) {
    return new W__class_name__({}, parent, editable);
  }

  /**
   * @override
   * @param {__class_name__JSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {W__class_name__}
   */
  static build (json, parent, editable = false) {
    return new W__class_name__(json, parent, editable);
  }

  /**
   * @override
   * @returns {ComponentContent}
   */
  get inspectorHTML () {
    return (
      NotInspectorAble()
    );
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