class WListItem extends Widget {
  #textEditor;
  constructor (json, parent, editable = false) {
    super(Component("li", "w-list-item"), parent, editable);
    this.childSupport = 1;
    this.removeMargin();
    this.#textEditor = WTextEditor.build({
      content: json.text,
      mode: "simple",
      doRequestNewLine: true
    }, this, editable);
    this.#textEditor.addListener("remove", () => this.remove());
    this.#textEditor.addListener("next-default", () => this.parentWidget?.nextDefault.call(this.parentWidget, this));
    this.appendWidget(this.#textEditor);
    if (editable) {
      this.appendEditGui();
    }
  }
  static default (parent, editable = false) {
    return new WListItem({
      text: []
    }, parent, editable);
  }
  static build (json, parent, editable = false) {
    return new WListItem(json, parent, editable);
  }
  get inspectorHTML () {
    return (
      NotInspectorAble()
    );
  }
  save () {
    return {
      type: "WListItem",
      text: this.#textEditor.save().content
    };
  }
  saveCompact () {
    return this.#textEditor.save().content;
  }
  focus() {
    this.children[0].focus();
  }
}
widgets.define("WListItem", WListItem);
