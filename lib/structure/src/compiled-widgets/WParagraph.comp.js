var WParagraph = class WParagraph {
  static build (json) {
    return html ({
      name: "p",
      className: "w-paragraph",
      content: json.text
    });
  }
  static edit (json) {
    const r = this.build(json);
    r.classList.add("edit");
    return r;
  }
  static destruct (element) {
    return {
      type: this.constructor.name,
      text: element.textContent
    }
  }
};
