var WColumn = class WColumn {
  static build (json) {
    return html ({
      className: "w-column",
      content: json.children.map(c => window[c.type].build(c))
    });
  }
  static edit (json) {
    return html ({
      className: "w-column",
      content: json.children.map(c => window[c.type].edit(c))
    });
  }
  static destruct (element) {
  }
};
