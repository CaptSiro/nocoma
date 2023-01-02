class WText extends Widget {
  static #newLine (content) {
    const div = document.createElement("div");
    div.classList.add("line");
    if (content === "") {
      div.innerHTML = "&#8203;";
    } else {
      div.textContent = content;
    }
    return div;
  }
  static #parseLines (lines, forceSingleLine = false) {
    if (forceSingleLine) {
      return this.#newLine(lines.reduce((acc, cur) => acc + cur, ""));
    }
    return lines.length !== 0
      ? lines.map(this.#newLine)
      : this.#newLine("");
  }
  constructor (root, parent) {
    super(root, parent);
    this.childSupport = "none";
  }
  static default (parent, editable) {
    return WText.build({ lines: [""] }, parent, editable);
  }
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
  getLine (index) {
    const lines = this.rootElement.querySelectorAll(".line");
    if (index < 0) {
      return lines[lines.length + index];
    }
    return lines[index];
  }
  getTextContent () {
    return Array.from(this.rootElement.querySelectorAll(".line"))
      .reduce((content, element) => content + element.textContent, "");
  }
  focus() {
    const range = document.createRange();
    const selection = window.getSelection();
    console.log(this);
    const lastLine = this.getLine(-1);
    let lastTextNode = undefined;
    for (const childNode of Array.from(lastLine.childNodes).reverse()) {
      if (childNode.nodeType === Node.TEXT_NODE) {
        lastTextNode = childNode;
      }
    }
    if (lastTextNode === undefined) {
      lastLine.innerHTML = "&#8203;";
      lastTextNode = lastLine;
    }
    range.setStart(lastTextNode, lastTextNode.textContent.length);
    range.collapse(true);
    selection.removeAllRanges();
    selection.addRange(range);
    lastLine.focus();
  }
}
widgets.define("WText", WText);
