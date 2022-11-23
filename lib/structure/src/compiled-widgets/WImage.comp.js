var WImage = class WImage {
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
  static destruct (element) {
  }
};
