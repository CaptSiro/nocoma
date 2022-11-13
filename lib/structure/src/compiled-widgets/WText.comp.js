var WText = class WText {
  static #parseLines (lines, forceSingleLine = false) {
    if (forceSingleLine) {
      return html({ content: lines.reduce((acc, cur) => acc + cur, "") })
    }
    return lines.map(str => html({ content: str }))
  }
  static build (json) {
    return html ({
      name: "p",
      content: this.#parseLines(json.lines, json.forceSingleLine),
      className: "w-text"
    });
  }
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
  static destruct (element) {
  }
};
