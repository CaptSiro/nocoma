var WText = class WText { // var is used because it creates reference on globalThis (window) object
  /**
   * @typedef TextJSONType
   * @prop {string[]} lines
   * @prop {boolean=} forceSingleLine
   * 
   * @typedef {TextJSONType & WidgetJSON} TextJSON
   */

  /**
   * @param {string[]} lines
   * @returns {HTMLElement}
   */
  static #parseLines (lines, forceSingleLine = false) {
    if (forceSingleLine) {
      return html({ content: lines.reduce((acc, cur) => acc + cur, "") })
    }

    return lines.map(str => html({ content: str }))
  }

  /**
   * @param {TextJSON} json
   * @returns {HTMLElement}
   */
  static build (json) {
    return html ({
      name: "p",
      content: this.#parseLines(json.lines, json.forceSingleLine),
      className: "w-text"
    });
  }

  /**
   * @param {TextJSON} json
   * @returns {HTMLElement}
   */
  static edit (json) {
    return html({
      name: "p",
      content: this.#parseLines(json.lines, json.forceSingleLine),
      attributes: {
        contenteditable: true,
        spellcheck: false,
      },
      className: ["w-text", "edit"],
      listeners: {
        blur: function (evt) {
          console.log("save");
          console.log(Array.from(this.childNodes).reduce((acc, cur, i, arr) => acc + cur.textContent + ((i != arr.length - 1) ? "<nl>" : ""), ""));
          console.dir(this);
        }
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