class WImage extends Widget {
  constructor (root, parent) {
    super(root, parent);
    this.childSupport = "none";
  }
  static default (parent) {
    return this.build({ src: AJAX.SERVER_HOME + "/public/images/theme-stock-pictures/__8219034641.png", alt: "Unnamed image" }, parent, true);
  }
  static build (json, parent, editable = false) {
    const img =  new WImage(html({
      className: "w-image-container",
      content: {
        name: "img",
        className: (()=>{
          const a = ["w-image"];
          if (json.height !== undefined || json.width !== undefined) {
            a.push("obey");
          }
          return a;
        })(),
        attributes: {
          src: json.src,
          alt: json.alt ?? "Unnamed image",
        }
      },
      modify: container => {
        if (json.height !== undefined || json.width !== undefined) {
          container.style.width = json.width ?? container.style.width;
          container.style.height = json.height ?? container.style.height;
        }
      },
    }), parent);
    if (editable === true) {
      img.appendEditGui();
    }
    return img;
  }
  get inspectorJSON () {
    return {
      elements: [{
        type: "Label",
        content: "Image"
      }]
    };
  }
  save () {
    return {
      type: "WImage"
    };
  }
}
widgets.define("WImage", WImage);
