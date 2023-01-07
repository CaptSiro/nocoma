class WHeading extends Widget {
  constructor (root, parent) {
    super(root, parent);
    this.childSupport = "none";
  }
  static default (parent) {
    return this.build({ level: 3, text: "Lorem ipsum" }, parent);
  }
  static build (json, parent, editable = false) {
    return new WHeading(
      Heading(
        Number(json.level ?? 3),
        "w-heading",
        json.text
      ), parent
    );
  }
  get inspectorJSON () {
    return {
      elements: [{
        type: "Label",
        content: "Heading"
      }]
    };
  }
  save () {
    return {
      type: "WHeading"
    };
  }
}
widgets.define("WHeading", WHeading);
