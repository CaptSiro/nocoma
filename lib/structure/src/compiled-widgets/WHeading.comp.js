var WHeading = class WHeading {
  static build (json) {
    return html ({
      name: "h" + Math.min(Math.max(0, json.level), 6),
      content: json.text,
      className: "w-heading"
    });
  }
  static edit (json) {
  }
  static destruct (element) {
  }
};
