class WSingleLine extends Widget {

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef SingleLineJSONType
   * @property {string=} text
   * 
   * @typedef {SingleLineJSONType & WidgetJSON} SingleLineJSON
   */

  /**
   * @param {SingleLineJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   */
  constructor (json, parent, editable = false) {
    super(Div("w-single-line-text"), parent);
    this.#createLine(json.text);
    this.childSupport = this.childSupport;
    
    if (editable !== true) return;
  
    this.rootElement.setAttribute("contenteditable", "true");
    this.rootElement.setAttribute("spellcheck", "false");
    this.rootElement.classList.add("edit");
  
    if (this.rootElement.textContent === "") {
      this.rootElement.classList.add("show-hint");
    }
  
    this.rootElement.addEventListener("keydown", evt => {
      if (evt.key === "Enter") {
        evt.preventDefault();
      }
    });
    
    this.rootElement.addEventListener("paste", evt => {
      evt.stopPropagation();
      evt.preventDefault();
  
      this.rootElement.textContent = (evt.clipboardData || window.clipboardData).getData("Text");
    });
  
    this.rootElement.addEventListener("keyup", () => {
      if (this.rootElement.textContent !== "") {
        this.rootElement.classList.remove("show-hint");
        return;
      }
    
      this.rootElement.classList.add("show-hint");
    });
  }
  
  #createLine (content) {
    content = String(content);
  
    if (content === "") {
      this.rootElement.innerHTML = "&#8203;";
    } else {
      this.rootElement.textContent = content;
    }
  }

  /**
   * @override
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WSingleLine}
   */
  static default (parent, editable = false) {
    return new WSingleLine({}, parent, editable);
  }

  /**
   * @override
   * @param {SingleLineJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WSingleLine}
   */
  static build (json, parent, editable = false) {
    return new WSingleLine(json, parent, editable);
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
   * @returns {SingleLineJSON}
   */
  save () {
    return {
      type: "WSingleLine",
      text: this.rootElement.textContent
    };
  }
}
widgets.define("WSingleLine", WSingleLine);