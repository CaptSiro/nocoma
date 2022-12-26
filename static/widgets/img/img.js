class WImage extends Widget {

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef ImageJSONType
   * @prop {string} src
   * @prop {string=} alt
   * @prop {string=} width
   * @prop {string=} height
   * 
   * @typedef {ImageJSONType & WidgetJSON} ImageJSON
   */

  /**
   * @param {HTMLElement} root
   * @param {Widget} parent
   */
  constructor (root, parent) {
    super(root, parent);
    this.childSupport = "none";
  }

  /**
   * @override
   * @param {Widget} parent
   * @returns {WImage}
   */
  static default (parent) {
    return this.build({ src: "", alt: "Unnamed image" }, parent, true);
  }

  /**
   * @override
   * @param {ImageJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WImage}
   */
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

  /**
   * @override
   * @returns {InspectorJSON}
   */
  get inspectorJSON () {
    return {
      elements: [{
        type: "Label",
        content: "Image"
      }]
    };
  }

  /**
   * @override
   * @returns {WidgetJSON}
   */
  save () {
    return {
      type: "WImage"
    };
  }
}
widgets.define("WImage", WImage);