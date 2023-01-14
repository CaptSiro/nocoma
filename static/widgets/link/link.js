class WLink extends Widget {

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef LinkJSONType
   * @property {string} label
   * @property {string} url
   * @property {string=} title
   * 
   * @typedef {LinkJSONType & WidgetJSON} LinkJSON
   */
  
  /**
   * @param {LinkJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   */
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

  /**
   * @override
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WLink}
   */
  static default (parent, editable = false) {
    return this.build({
      url: "",
      label: "link",
      title: "link",
    }, parent, editable);
  }

  /**
   * @override
   * @param {LinkJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WLink}
   */
  static build (json, parent, editable = false) {
    return new WLink(json, parent);
  }

  /**
   * @override
   * @returns {ComponentContent}
   */
  get inspectorHTML () {
    return (
      TitleInspector("Link")
    );
  }

  /**
   * @override
   * @returns {WidgetJSON}
   */
  save () {
    return {
      type: "WLink"
    };
  }
}
widgets.define("WLink", WLink);