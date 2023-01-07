class WLink extends Widget {
  constructor (root, parent) {
    super(root, parent);
    this.childSupport = "none";
  }
  static default (parent) {
    return new WLink(Link("#", "w-link", "link", {
      attributes: {
        title: "link",
        target: "_blank"
      }
    }), parent);
  }
  static build (json, parent, editable = false) {
    return new WLink(Link(json.url, "w-link", String(json.label ?? json.title ?? json.url), {
      attributes: {
        target: "_blank",
        title: json.title ?? ""
      }
    }), parent);
  }
  get inspectorJSON () {
    return {
      elements: [{
        type: "Label",
        content: "Link"
      }]
    };
  }
  save () {
    return {
      type: "WLink"
    };
  }
}
widgets.define("WLink", WLink);
