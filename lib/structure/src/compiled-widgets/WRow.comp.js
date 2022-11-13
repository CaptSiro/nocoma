var WRow = class WRow {
  static build (json) {
    return html ({
      className: "w-row",
      content: json.children.map(o => {
        return window[o.type].build(o);
      })
    });
  }
  static edit (json) {
  }
  static destruct (element) {
  }
};
