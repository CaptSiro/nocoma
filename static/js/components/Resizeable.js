class Resizeable {
  #e;

  /**
   * @typedef PrecalculateStyles
   * @prop {number} borderX
   * @prop {number} borderY
   * @prop {number} paddingX
   * @prop {number} paddingY
   * @prop {number} contentMinX
   * @prop {number} contentMinY
   * @prop {number} contentMaxX
   * @prop {number} contentMaxY
   */
  /** @type {PrecalculateStyles} */
  #styles = {};

  /** @type {HTMLElement} */
  content;
  
  #listeners = {};
  on (event, callback) {
    if (this.#listeners[event] === undefined) {
      this.#listeners[event] = [callback];
    }
    
    this.#listeners[event].push(callback);
  }
  
  dispatch (event, ...args) {
    for (const listener of (this.#listeners[event] ?? [])) {
      listener(...args);
    }
  }
  
  RADIUS_LISTENER () {
    return radius => {
      this.#e.style.borderRadius = radius + "%";
    }
  }
  
  RESIZE_LISTENER () {
    return (width, height) => {
      this.#e.style.width = width + "px";
      this.#e.style.height = height + "px";
    }
  }
  
  /**
   * @typedef {"diagonal" | "d" | "horizontal" | "h" | "vertical" | "v"} Axes
   */
  /**
   * @param {HTMLElement} element
   * @param {Axes=} axes
   * @param {"enabled"=} borderRadius
   */
  constructor (element, {
    axes,
    borderRadius
  } = {}) {
    this.#e = element;

    this.content = Div("content", this.#e.children);
    this.#e.appendChild(this.content);

    this.#e.classList.add("resizeable", "boundaries");

    axes = axes ?? this.#e.getAttribute("axes");

    this.#e.appendChild(
      Div("handles", [
        ...OptionalComponents(axes === "vertical" || axes === "v" || axes === "diagonal" || axes === "d", [
          Div("handle x-cen y-min vertical", __, {
            listeners: {
              pointerdown: this.#pointerDownHandlerFactory((evt => {
                this.reverseResizeY(evt);
              }).bind(this), this.#e, this)
            }
          }),
          Div("handle x-cen y-max vertical", __, {
            listeners: {
              pointerdown: this.#pointerDownHandlerFactory((evt => {
                this.resizeY(evt);
              }).bind(this), this.#e, this)
            }
          })
        ]),
        ...OptionalComponents(axes === "horizontal" || axes === "h" || axes === "diagonal" || axes === "d", [
          Div("handle x-min y-cen horizontal", __, {
            listeners: {
              pointerdown: this.#pointerDownHandlerFactory((evt => {
                this.reverseResizeX(evt);
              }).bind(this), this.#e, this)
            }
          }),
          Div("handle x-max y-cen horizontal", __, {
            listeners: {
              pointerdown: this.#pointerDownHandlerFactory((evt => {
                this.resizeX(evt);
              }).bind(this), this.#e, this)
            }
          })
        ]),
        OptionalComponent(axes === "diagonal" || axes === "d",
          Div("br-handle", __, {
            listeners: {
              pointerdown: this.#pointerDownHandlerFactory((evt => {
                this.scaleBorderRadius(evt);
              }).bind(this), this.#e, this)
            }
          })
        ),
        ...OptionalComponents(borderRadius ?? this.#e.getAttribute("border-radius") === "enabled", [
          Div("handle x-min y-min diagonal", __, {
            listeners: {
                pointerdown: this.#pointerDownHandlerFactory((evt => {
                this.reverseResizeX(evt);
                this.reverseResizeY(evt);
              }).bind(this), this.#e, this)
            }
          }),
          Div("handle x-max y-min reverse-diagonal", __, {
            listeners: {
              pointerdown: this.#pointerDownHandlerFactory((evt => {
                this.resizeX(evt);
                this.reverseResizeY(evt);
              }).bind(this), this.#e, this)
            }
          }),
          Div("handle x-min y-max reverse-diagonal", __, {
            listeners: {
              pointerdown: this.#pointerDownHandlerFactory((evt => {
                this.reverseResizeX(evt);
                this.resizeY(evt);
              }).bind(this), this.#e, this)
            }
          }),
          Div("handle x-max y-max diagonal", __, {
            listeners: {
              pointerdown: this.#pointerDownHandlerFactory((evt => {
                this.resizeX(evt);
                this.resizeY(evt);
              }).bind(this), this.#e, this)
            }
          })
        ])
      ])
    );
  }



  /**
   * @param {(evt: PointerEvent)=>void} func 
   * @param {HTMLElement} element
   * @param {Resizeable} that
   * @returns {(evt: PointerEvent)=>void}
   */
  #pointerDownHandlerFactory (func, element, that) {
    return function (evt) {
      /** @type {HTMLElement} */
      const self = this;

      self.setPointerCapture(evt.pointerId);
      element.classList.add("resizing");
      that.recalculateStyles();
      
      self.addEventListener("pointermove", func);

      self.addEventListener("pointerup", () => {
        element.classList.remove("resizing");
        self.removeEventListener("pointermove", func);
      }, { once: true });
      self.addEventListener("pointercancel", _ => console.log("cancel"))
    }
  }



  recalculateStyles () {
    const compStyles = window.getComputedStyle(this.#e);
    /** @type {PrecalculateStyles} */
    const styles = {};

    if (compStyles.border !== "") {
      const b = /([0-9]+)(.*)/.exec(compStyles.borderTop)[1];
      
      let border = +b * 2;

      styles.borderX = border;
      styles.borderY = border;
    } else {
      const bTop = this.#getCSSNumberProp(compStyles, "borderTopWidth");
      const bBottom = this.#getCSSNumberProp(compStyles, "borderBottomWidth");
      const bLeft = this.#getCSSNumberProp(compStyles, "borderLeftWidth");
      const bRight = this.#getCSSNumberProp(compStyles, "borderRightWidth");

      styles.paddingX = (+bRight) + (+bLeft);
      styles.paddingY = (+bTop) + (+bBottom);
    }

    if (compStyles.padding !== "") {
      const p = /([0-9]+)(.*)/.exec(compStyles.padding);
      
      let padding = +p[1] * 2;

      styles.paddingX = padding;
      styles.paddingY = padding;
    } else {
      const pTop = this.#getCSSNumberProp(compStyles, "paddingTop");
      const pBottom = this.#getCSSNumberProp(compStyles, "paddingBottom");
      const pLeft = this.#getCSSNumberProp(compStyles, "paddingLeft");
      const pRight = this.#getCSSNumberProp(compStyles, "paddingRight");

      styles.paddingX = (+pRight[1]) + (+pLeft[1]);
      styles.paddingY = (+pTop[1]) + (+pBottom[1]);
    }




    const min = this.#e.getAttribute("min-size");
    const minSize = min != null
      ? /(.+) (.+)/.exec(min)
      : ["", "full", "full"];

    let cWidth = 0, cHeight = 0;

    for (const child of this.content.children) {
      if (!child.classList.contains("handles")) {
        if (minSize[1] === "full") {
          cWidth += child.offsetWidth;
        }

        if (minSize[2] === "full") {
          cHeight += child.offsetHeight;
        }
      }
    }

    if (minSize[1] !== "full") {
      cWidth = minSize[1] === "none" || isNaN(+minSize[1])
        ? 32
        : +minSize[1];
    }

    if (minSize[2] !== "full") {
      cHeight = minSize[2] === "none" || isNaN(+minSize[2])
        ? 32
        : +minSize[2];
    }

    styles.contentX = cWidth;
    styles.contentY = cHeight;



    const max = this.#e.getAttribute("max-size");
    const maxSize = max != null
      ? /(.+) (.+)/.exec(max)
      : ["", "none", "none"];
    styles.contentMaxX = maxSize[1] === "none" || isNaN(+maxSize[1])
      ? Infinity
      : +maxSize[1];
    styles.contentMaxY = maxSize[2] === "none" || isNaN(+maxSize[2])
      ? Infinity
      : +maxSize[2];

    this.#styles = styles;
  }


  /**
   * @param {CSSStyleDeclaration} compStyles 
   * @param {string} cssProp 
   * @returns {number}
   */
  #getCSSNumberProp (compStyles, cssProp) {
    return compStyles[cssProp] === ""
      ? 0
      : /([0-9]+)(.*)/.exec(compStyles[cssProp])[1]
  }



  /**
   * @param {PointerEvent} evt 
   */
  resizeX (evt) {
    const parentWidth = this.#e.clientWidth;
    const parentLeft = this.#e.getBoundingClientRect().left;
    const cursorLeft = evt.clientX;
    const difference = cursorLeft - (parentLeft + parentWidth);
    
    const width = parentWidth + difference - this.#styles.paddingX - this.#styles.borderX;
    if (this.#styles.contentX < width && width < this.#styles.contentMaxX) {
      this.dispatch("resize", width, this.#e.scrollHeight);
    }
  }

  /**
   * @param {PointerEvent} evt 
   */
  reverseResizeX (evt) {
    const parentWidth = this.#e.clientWidth;
    const parentLeft = this.#e.getBoundingClientRect().left;
    const cursorLeft = evt.clientX;
    const difference = parentLeft - cursorLeft;
    
    const width = parentWidth + difference - this.#styles.paddingX - this.#styles.borderX;
    if (this.#styles.contentX < width && width < this.#styles.contentMaxX) {
      this.dispatch("resize", width, this.#e.scrollHeight);
    }
  }


  /**
   * @param {PointerEvent} evt 
   */
  resizeY (evt) {
    const parentHeight = this.#e.clientHeight;
    const parentTop = this.#e.getBoundingClientRect().top;
    const cursorTop = evt.clientY;
    const difference = cursorTop - (parentTop + parentHeight);
  
    const height = parentHeight + difference - this.#styles.paddingY - this.#styles.borderY;
    if (this.#styles.contentY < height && height < this.#styles.contentMaxY) {
      this.dispatch("resize", this.#e.scrollWidth, height);
    }
  }

  /**
   * @param {PointerEvent} evt 
   */
  reverseResizeY (evt) {
    const parentHeight = this.#e.clientHeight;
    const parentTop = this.#e.getBoundingClientRect().top;
    const cursorTop = evt.clientY;
    const difference = parentTop - cursorTop;
    
    const height = parentHeight + difference - this.#styles.paddingY - this.#styles.borderY;
    if (this.#styles.contentY < height && height < this.#styles.contentMaxY) {
      this.dispatch("resize", this.#e.scrollWidth, height);
    }
  }

  /**
   * @param {PointerEvent} evt 
   */
  scaleBorderRadius (evt) {
    const HANDLE_SIZE = 7;
    const HANDLE_OFFSET = 10;

    const parentWidth = this.#e.clientWidth / 5;
    const parentLeft = this.#e.getBoundingClientRect().left;
    const cursorLeft = evt.clientX;
    const differenceX = cursorLeft - parentLeft;
    const percentageX = clamp(0, 0.5, (differenceX - HANDLE_SIZE - HANDLE_OFFSET) / (parentWidth));
    
    const parentHeight = this.#e.clientHeight / 5;
    const parentTop = this.#e.getBoundingClientRect().top;
    const cursorTop = evt.clientY;
    const differenceY = cursorTop - parentTop;
    const percentageY = clamp(0, 0.5, (differenceY - HANDLE_SIZE - HANDLE_OFFSET) / (parentHeight));

    const percentage = Math.max(percentageX, percentageY) * 100;

    this.dispatch("radius", percentage);
    // this.#contentBox.style.borderRadius = percentage + "%";

    const handlePos = `calc(${percentage / 5}% + 10px)`;
    evt.target.style.top = handlePos;
    evt.target.style.left = handlePos;
  }
}