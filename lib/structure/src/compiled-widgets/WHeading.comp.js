class WHeading extends Widget {
  constructor (root, parent) {
    super(root, parent);
    this.childSupport = "none";
  }
  static default (parent) {
    return this.build({ level: 3, text: "Lorem ipsum" }, parent);
  }
  static build (json, parent, editable = false) {
    return new WHeading(html({
      name: "h" + Math.min(Math.max(1, json.level ?? 3), 6),
      textContent: json.text,
      className: "w-heading"
    }), parent);
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
