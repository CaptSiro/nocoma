var WLink = class WLink extends Widget {
  constructor (root, parent) {
    super(root, parent);
    this.childSupport = "none";
  }
  static default (parent) {
    return new WLink(html({
      name: "a",
      content: "link",
      attributes: {
        href: "#",
        title: "link",
        target: "_blank"
      }
    }), parent);
  }
  static build (json, parent, editable = false) {
    return new WLink(html({
      name: "a",
      content: json.label ?? json.title ?? json.url,
      attributes: {
        href: json.url,
        title: json.title ?? "",
        target: "_blank"
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
};
