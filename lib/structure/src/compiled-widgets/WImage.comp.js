class WImage extends Widget {
  #json;
  #resizeable;
  #imageElement;
  #imageContainer;
  #dimensions = new Observable([0, 0, 0, 0]);
  static VIEWPORT_MARGIN = 16;
  constructor (json, parent, editable = false) {
    const image = Img(
      json.src !== undefined
        ? WImage.createSourceURL(json.src)
        : AJAX.SERVER_HOME + "/public/images/backgrounds-mono/0.png",
      json.alt ?? "Unnamed image",
      "w-image"
    );
    const imageContainer = Div("w-image-container", image);
    super(
      Div("w-image-mount", imageContainer),
      parent,
      editable
    );
    this.#imageElement = image;
    this.#imageContainer = imageContainer;
    this.childSupport = "none";
    if (editable) {
      this.#json = new Observable(json);
      this.#json.onChange(descriptor => {
        this.#imageElement.src = WImage.createSourceURL(descriptor.src);
      });
      this.#resizeable = new Resizeable(imageContainer, {axes: "diagonal", borderRadius: "enabled"});
      this.#resizeable.on("resize", (width, height) => {
        const oldDimensions = this.#dimensions.value;
        this.#dimensions.value = [
          clamp(0.05, 1, width / this.rootElement.getBoundingClientRect().width),
          clamp(16, 4096, height),
          oldDimensions[2]
        ];
      });
      this.#resizeable.on("radius", borderRadius => {
        this.#dimensions.setProperty(2, borderRadius);
      });
      this.#resizeable.content.style.width = "unset";
      this.#resizeable.content.style.height = "unset";
      this.appendEditGui();
    } else {
      this.#imageContainer.style.overflow = "hidden";
    }
    this.#dimensions.onChange(([width, height, borderRadius]) => {
      const oldDimensions = this.#dimensions.value;
      oldDimensions[3] =
        (this.rootElement.getBoundingClientRect().width * width) / height;
      this.#dimensions.setValueSafe(oldDimensions);
      this.setImageDimensions(width, height, borderRadius);
    });
    this.#dimensions.value = [json.width ?? 0.70, json.height ?? 400, json.borderRadius ?? 0, json.aspectRatio];
    const resizeListener = () => {
      if (this.#dimensions.value[3] === undefined || typeof this.#dimensions.value[3] !== "number") return;
      const oldDimensions = this.#dimensions.value;
      oldDimensions[1] =
        (this.rootElement.getBoundingClientRect().width * oldDimensions[0]) / this.#dimensions.value[3];
      this.#dimensions.setValueSafe(oldDimensions);
      this.setImageDimensions(oldDimensions[0], oldDimensions[1], oldDimensions[2]);
    };
    onViewportResize(resizeListener);
    if (viewportDimensions !== undefined) {
      resizeListener(viewportDimensions);
      return;
    }
    viewportResize();
  }
  setImageDimensions (widthPercentage, height, borderRadius) {
    this.#imageContainer.style.width = (widthPercentage * 100) + "%";
    if (this.editable === false) {
      this.#imageContainer.style.height = height + "px";
      this.#imageContainer.style.borderRadius = borderRadius + "%";
      return;
    }
    this.#resizeable.content.style.height = height + "px";
    this.#resizeable.content.style.borderRadius = borderRadius + "%";
    this.#resizeable.setRadiusHandlesPositions(borderRadius);
  }
  static createSourceURL (src) {
    return `${AJAX.SERVER_HOME}/file/${webpage.src}/${src}`;
  }
  static default (parent, editable = false) {
    return this.build({
      alt: "Unnamed image",
      width: 0.75,
      height: 400
    }, parent, editable);
  }
  static build (json, parent, editable = false) {
    return new WImage(json, parent, editable);
  }
  get inspectorHTML () {
    return [
      TitleInspector("Image"),
      HRInspector(),
      TitleInspector("Properties"),
      TextFieldInspector(this.#json.value.alt, (value, parentElement) => {
        this.#json.setProperty("alt", value);
        validated(parentElement);
      }, "Text description:"),
      Div("i-row", [
        Span(__, "Image:"),
        Button("button-like-main", "Select", evt => {
          const win = showWindow("file-select");
          win.dataset.multiple = "false";
          win.dataset.fileType = "image";
          win.dispatchEvent(new Event("fetch"));
          win.onsubmit = submitEvent => {
            this.#json.setProperty("src", submitEvent.detail[0].serverName);
            validated(evt.target.parentElement);
          };
        })
      ])
    ];
  }
  save () {
    return {
      type: "WImage",
      src: this.#json.value.src,
      alt: this.#json.value.alt,
      width: this.#dimensions.value[0],
      height: this.#dimensions.value[1],
      borderRadius: this.#dimensions.value[2],
      aspectRatio: this.#dimensions.value[3],
    };
  }
}
widgets.define("WImage", WImage);
