var WImage = class WImage { // var is used because it creates reference on globalThis (window) object

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
   * @param {ImageJSON} json
   * @returns {HTMLElement}
   */
  static build (json) {
    return html({
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
    });
  }
  
  /**
   * @param {ImageJSON} json
   * @returns {HTMLElement}
   */
  static edit (json) {
    return html ({
      className: "w-image-container",
      content: {
        name: "img",
        className: "w-image",
        attributes: {
          src: json.src,
          alt: json.alt ?? "Unnamed image",
        }
      }
    });
  }

  /**
   * @param {HTMLElement} element
   * @returns {JSON}
   */
  static destruct (element) {

  }
};