class WSingleLine extends Widget {
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
  static default (parent, editable = false) {
    return new WSingleLine({}, parent, editable);
  }
  static build (json, parent, editable = false) {
    return new WSingleLine(json, parent, editable);
  }
  get inspectorHTML () {
    return (
      NotInspectorAble()
    );
  }
  save () {
    return {
      type: "WSingleLine",
      text: this.rootElement.textContent
    };
  }
}
widgets.define("WSingleLine", WSingleLine);
