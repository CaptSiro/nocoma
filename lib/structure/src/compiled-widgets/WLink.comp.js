var WLink = class WLink {
  static build (json) {
    return html ({
      name: "a",
      content: json.label ?? json.title ?? json.url,
      attributes: {
        href: json.url,
        title: json.title ?? "",
        target: "_blank"
      }
    });
  }
  static edit (json) {
    return html ({
      name: "a",
      content: json.label ?? json.title ?? json.url,
      attributes: {
        href: json.url,
        title: json.title ?? "",
        target: "_blank"
      }
    });
  }
  static destruct (element) {
  }
};
