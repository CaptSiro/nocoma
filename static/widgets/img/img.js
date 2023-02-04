class WImage extends Widget {

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef ImageJSONType
   * @property {string} src
   * @property {string=} alt
   * @property {string=} width
   * @property {string=} height
   * 
   * @typedef {ImageJSONType & WidgetJSON} ImageJSON
   */
  
  /**
   * @param {ImageJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   */
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

  /**
   * @override
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WImage}
   */
  static default (parent, editable = false) {
    return this.build({
      src: AJAX.SERVER_HOME + "/public/images/theme-stock-pictures/__8219034641.png",
      alt: "Unnamed image"
    }, parent, editable);
  }

  /**
   * @override
   * @param {ImageJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WImage}
   */
  static build (json, parent, editable = false) {
    return new WImage(json, parent);
  }

  /**
   * @override
   * @returns {ComponentContent}
   */
  get inspectorHTML () {
    return (
      TitleInspector("Image")
    );
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