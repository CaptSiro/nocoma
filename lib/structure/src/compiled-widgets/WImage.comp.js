class WImage extends Widget {
  constructor (json, parent, editable = false) {
    super(
      Div("w-image-container",
        Img(json.src, json.alt ?? "Unnamed image",
          "w-image"
          + (json.height !== undefined || json.width !== undefined
            ? " obey"
            : "")
        ), {
          modify: container => {
            if (json.height !== undefined || json.width !== undefined) {
              container.style.width = json.width ?? container.style.width;
              container.style.height = json.height ?? container.style.height;
            }
          }
        }
      ),
      parent,
      editable
    );
    this.childSupport = "none";
    if (editable) {
      this.appendEditGui();
    }
  }
  static default (parent, editable = false) {
    return this.build({
      src: AJAX.SERVER_HOME + "/public/images/theme-stock-pictures/__8219034641.png",
      alt: "Unnamed image"
    }, parent, editable);
  }
  static build (json, parent, editable = false) {
    return new WImage(json, parent);
  }
  get inspectorHTML () {
    return (
      TitleInspector("Image")
    );
  }
  save () {
    return {
      type: "WImage"
    };
  }
}
widgets.define("WImage", WImage);
