var WText = class WText extends Widget {
  static #parseLines (lines, forceSingleLine = false) {
    if (forceSingleLine) {
      return html({ content: lines.reduce((acc, cur) => acc + cur, "")})
    }
    return lines.length != 0 ? lines.map(str => html({ content: str })) : document.createElement("div");
  }
  constructor (root, parent) {
    super(root, parent);
    this.childSupport = "none";
  }
  static default (parent) {
    return new WText(html({}), parent);
  }
  static build (json, parent, editable = false) {
    const text = new WText(html({
      name: "p",
      content: this.#parseLines(json.lines, json.forceSingleLine),
      className: "w-text",
    }), parent);
    if (editable == true) {
      text.appendEditGui();
      text.rootElement.setAttribute("contenteditable", true);
      text.rootElement.setAttribute("spellcheck", false);
      text.rootElement.classList.add("edit");
      if (text.rootElement.textContent == "") {
        text.rootElement.classList.add("show-hint");
      }
      text.rootElement.addEventListener("input", function (evt) {
        if (this.textContent == "") {
          this.classList.add("show-hint");
          if (this.children.length == 0) {
            this.append(document.createElement('div'));
          }
        } else {
          this.classList.remove("show-hint");
        }
      });
    }
    return text;
  }
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
        blur: function (evt) {
          console.log("save");
          console.log(Array.from(this.childNodes).reduce((acc, cur, i, arr) => acc + cur.textContent + ((i != arr.length - 1) ? "<nl>" : ""), ""));
          console.dir(this);
        }
      }
    }), parent);
  }
  get inspectorJSON () {
    return {
      elements: [{
        type: "Label",
        content: "Text"
      }]
    };
  }
  save () {
    return {
      type: "WText"
    };
  }
};
