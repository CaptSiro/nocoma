var WText = class WText extends Widget { // var is used because it creates reference on globalThis (window) object

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef TextJSONType
   * @prop {string[]} lines
   * @prop {boolean=} forceSingleLine force whole content into single line
   * 
   * @typedef {TextJSONType & WidgetJSON} TextJSON
   */

  
  /**
   * @param {string[]} lines
   * @param {boolean} forceSingleLine
   * @returns {HTMLElement}
   */
  static #parseLines (lines, forceSingleLine = false) {
    if (forceSingleLine) {
      return html({ content: lines.reduce((acc, cur) => acc + cur, "")})
    }

    return lines.length !== 0 ? lines.map(str => html({ content: str })) : document.createElement("div");
  }



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
   * @returns {WText}
   */
  static default (parent) {
    return new WText(html({}), parent);
  }

  /**
   * @override
   * @param {TextJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WText}
   */
  static build (json, parent, editable = false) {
    const text = new WText(html({
      name: "p",
      content: this.#parseLines(json.lines, json.forceSingleLine),
      className: "w-text",
    }), parent);

    if (editable === true) {
      text.appendEditGui();
      text.rootElement.setAttribute("contenteditable", "true");
      text.rootElement.setAttribute("spellcheck", "false");
      text.rootElement.classList.add("edit");

      if (text.rootElement.textContent === "") {
        text.rootElement.classList.add("show-hint");
      }

      text.rootElement.addEventListener("input", function () {
        if (this.textContent === "") {
          this.classList.add("show-hint");
          if (this.children.length === 0) {
            this.append(document.createElement('div'));
          }
        } else {
          this.classList.remove("show-hint");
        }
      });
    }

    return text;
  }

  /**
   * @override
   * @param {TextJSON} json
   * @param {Widget} parent
   * @returns {WText}
   */
  static edit (json, parent) {
    return new WText(html({
      name: "p",
      content: this.#parseLines(json.lines, json.forceSingleLine),
      attributes: {
        contenteditable: true,
        spellcheck: false,
      },
      className: ["w-text", "edit"],
      listeners: {
        blur: function () {
          console.log("save");
          console.log(Array.from(this.childNodes).reduce((acc, cur, i, arr) => acc + cur.textContent + ((i !== arr.length - 1) ? "<nl>" : ""), ""));
          console.dir(this);
        }
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
        content: "Text"
      }]
    };
  }

  /**
   * @override
   * @returns {WidgetJSON}
   */
  save () {
    return {
      type: "WText"
    };
  }
};