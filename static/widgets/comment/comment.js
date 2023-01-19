class WComment extends Widget {

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef CommentJSONType
   * @property {string=} text
   * 
   * @typedef {CommentJSONType & WidgetJSON} CommentJSON
   */

  /**
   * @param {CommentJSON} json
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
   * @returns {WComment}
   */
  static default (parent, editable = false) {
    return new WComment({}, parent, editable);
  }

  /**
   * @override
   * @param {CommentJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WComment}
   */
  static build (json, parent, editable = false) {
    return new WComment({}, parent, editable);
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
      type: "WComment"
    };
  }
}
widgets.define("WComment", WComment);