var WParagraph = class WParagraph {
  /**
   * @typedef ParagraphJSONType
   * @prop {string} text
   * 
   * @typedef {ParagraphJSONType & WidgetJSON} ParagraphJSON
   */

  /**
   * @param {ParagraphJSON} json
   * @returns {HTMLElement}
   */
  static build (json) {
    return html ({
      name: "p",
      className: "w-paragraph",
      content: json.text
    });
  }

  /**
   * @param {ParagraphJSON} json
   * @returns {HTMLElement}
   */
  static edit (json) {
    const r = this.build(json);
    r.classList.add("edit");
    return r;
  }

  /**
   * @param {HTMLElement} element
   * @returns {ParagraphJSON}
   */
  static destruct (element) {
    return {
      type: this.constructor.name,
      text: element.textContent
    }
  }
};