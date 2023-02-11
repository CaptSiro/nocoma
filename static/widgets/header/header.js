class WHeader extends Widget {

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef HeaderJSONType
   * @property {string=} imageURL
   * @property {"start" | "center" | "end"=} titleAlign
   * @property {string=} titleColor
   *
   * @typedef {HeaderJSONType & WidgetJSON} HeaderJSON
   */

  /**
   * @param {HeaderJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   */
  constructor (json, parent, editable = false) {
    super(Div("w-header center"), parent, editable);
    this.removeMargin();
    this.childSupport = this.childSupport;
  
    const heading = Heading(1, "page-title", webpage.title, {
      attributes: {
        title: "Edit->Website Properties->Title",
      }
    });
    const headingContainer = (
      Div("heading-container", [
        heading,
        Span(__, new Date(webpage.timeCreated).toLocaleDateString("en-GB", {
          year: "numeric",
          month: "2-digit",
          day: "2-digit",
        }))
      ], {
        attributes: {
          style: `text-align: ${json.titleAlign ?? "center"};color: ${json.titleColor ?? "var(--text-color-0)"}`
        }
      })
    );
    
    const root = this.getRoot();
    if (root.json?.isHeaderIncluded !== true) {
      this.rootElement.classList.add("display-none");
    }
  
    if (root.json?.webpage?.thumbnail !== undefined) {
      this.rootElement.style.backgroundImage = `url(${AJAX.SERVER_HOME}/file/${webpage.src}/${root.json.webpage.thumbnail})`;
    }
    
    root.addJSONListener?.call(root, json => {
      heading.textContent = json.webpage.title;
      this.rootElement.classList.toggle("display-none", !json.isHeaderIncluded);
      this.rootElement.style.backgroundImage = json.webpage.thumbnail !== undefined
        ? `url(${AJAX.SERVER_HOME}/file/${webpage.src}/${json.webpage.thumbnail})`
        : "";
      headingContainer.style.textAlign = json.headerTitleAlign ?? "center";
      headingContainer.style.color = json.headerTitleColor ?? "var(--text-color-0)";
    });
    
    this.rootElement.appendChild(headingContainer);
    
    if (editable) {
      const resizeListener = dimensions => {
        this.rootElement.style.width = dimensions.width + "px";
        this.rootElement.style.height = dimensions.height + "px";
      };
      
      onViewportResize(resizeListener);

      if (viewportDimensions !== undefined) {
        resizeListener(viewportDimensions);
        return;
      }
      
      viewportResize();
    }
  }

  /**
   * @override
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WHeader}
   */
  static default (parent, editable = false) {
    return new WHeader({}, parent, editable);
  }

  /**
   * @override
   * @param {HeaderJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WHeader}
   */
  static build (json, parent, editable = false) {
    return new WHeader(json, parent, editable);
  }

  /**
   * @override
   * @returns {ComponentContent}
   */
  get inspectorHTML () {
    return (
      NotInspectorAble()
    );
  }

  /**
   * @override
   * @returns {WidgetJSON}
   */
  save () {
    return {
      type: "WHeader"
    };
  }
  
  isSelectAble() {
    return false;
  }
}
widgets.define("WHeader", WHeader);