//TODO: margin auto ->|  content  |<- margin auto
class WPage extends ContainerWidget {

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef PageJSONType
   * @property {number=} width
   * @property {boolean=} forceFullscreen
   *
   * @typedef {PageJSONType & WidgetJSON} PageJSON
   */
  
  /**
   * @param {PageJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   */
  constructor (json, parent, editable = false) {
    //! NOTE: page widget does not import any children widget. That is root's job
    super(
      Div("w-page" + (json.forceFullscreen ? " fullscreen" : "")),
      parent,
      editable
    );
    this.removeMargin();
    this.makeOutsideChildrenDragNotAllowed();
    this.childSupport = this.childSupport;
    
    if (json.forceFullscreen !== true) {
      this.rootElement.style.maxWidth = (json.width ?? 960) + "px";
    }
  }

  /**
   * @override
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WPage}
   */
  static default (parent, editable = false) {
    return new WPage({}, parent);
  }

  /**
   * @override
   * @param {PageJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WPage}
   */
  static build (json, parent, editable = false) {
    return new WPage(json, parent);
  }

  /**
   * @override
   * @returns {ComponentContent}
   */
  get inspectorHTML () {
    return (
      NotInspectorAble()
    )
  }

  /**
   * @override
   * @returns {WidgetJSON}
   */
  save () {
    return {
      type: "WPage"
    };
  }
  
  /** @override */
  remove () {
    console.error("WRoot cannot be removed.");
  }
}
widgets.define("WPage", WPage);