class WQuote extends Widget {
  #textEditor;
  #authorEditor;
  constructor (json, parent, editable = false) {
    super(Div("w-quote", [
      Span("decorative", ',,')
    ]), parent, editable);
    this.childSupport = this.childSupport;
    this.#textEditor = WTextEditor.build({
      content: json.text ?? [],
      mode: "simple",
      forceSingleLine: false,
      hint: "Life’s good, you should get one."
    }, this, editable);
    this.#authorEditor = WTextEditor.build({
      content: json.author ?? [],
      mode: "simple",
      forceSingleLine: true,
      hint: "Unnamed author"
    }, this, editable);
    this.rootElement.append(
      Div("text", [
        this.#textEditor.rootElement,
      ]),
      Div("author", [
        Span("decorative", "-"),
        this.#authorEditor.rootElement
      ])
    );
    if (editable) {
      this.appendEditGui();
    }
  }
  static default (parent, editable = false) {
    return new WQuote({}, parent, editable);
  }
  static build (json, parent, editable = false) {
    return new WQuote(json, parent, editable);
  }
  get inspectorHTML () {
    return (
      NotInspectorAble()
    );
  }
  save () {
    return {
      type: "WQuote",
      text: this.#textEditor.save().content,
      author: this.#authorEditor.save().content,
    };
  }
}
widgets.define("WQuote", WQuote);
