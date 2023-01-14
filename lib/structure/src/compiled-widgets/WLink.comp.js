class WLink extends Widget {
  constructor (json, parent, editable = false) {
    super(
      Link(json.url, "w-link", String(json.label ?? json.title ?? json.url), {
        attributes: {
          target: "_blank",
          title: json.title ?? ""
        }
      }),
      parent
    );
    this.childSupport = "none";
  }
  static default (parent, editable = false) {
    return this.build({
      url: "",
      label: "link",
      title: "link",
    }, parent, editable);
  }
  static build (json, parent, editable = false) {
    return new WLink(json, parent);
  }
  get inspectorHTML () {
    return (
      TitleInspector("Link")
    );
  }
  save () {
    return {
      type: "WLink"
    };
  }
}
widgets.define("WLink", WLink);
