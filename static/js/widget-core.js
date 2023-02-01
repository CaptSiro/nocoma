/**
 * @typedef WidgetJSON
 * @property {string=} type
 * @property {Object.<string, string>=} style
 * @property {WidgetJSON[]=} children
 */
class Widget {
  /**
   * @typedef {"none"|"multiple"|number} WidgetChildSupport
   */

  /** @type {WidgetChildSupport} */
  #childSupport = "multiple";

  /** @param {WidgetChildSupport} value */
  set childSupport (value) { this.#childSupport = value; }
  get childSupport () { return this.#childSupport; }

  /**
   * @param {HTMLElement} root 
   * @param {Widget} parent
   * @param {boolean} editable
   */
  constructor (root, parent, editable = false) {
    this.rootElement = root;
    this.rootElement.widget = this;
    this.editable = editable;
    this.rootElement.classList.add("widget", "margin");
  
    if (window.inspect !== undefined) {
      this.rootElement.addEventListener("click", this.inspectHandler.bind(this));
    }

    this.parentWidget = parent;

    /** @type {Widget[]} */
    this.children = [];
  }
  
  /**
   * @param {Event} evt
   */
  inspectHandler (evt) {
    if (currentlyInspecting === this) {
      evt.stopInspector = true;
      return;
    }
    
    const inspectorHTML = this.inspectorHTML;
    if (inspectorHTML === NotInspectorAble() || evt.stopInspector === true || currentlyInspecting === this) return;
    
    inspect(inspectorHTML, this);
    evt.stopInspector = true;
    // evt.stopPropagation();
  }

  /**
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {Widget}
   */
  static default (parent, editable) {
    return new Widget(document.createElement("div"), parent);
  }

  /**
   * @param {WidgetJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {Widget}
   */
  static build (json, parent, editable = false) {
    return new Widget(document.createElement("div"), parent);
  }

  /**
   * @returns {ComponentContent}
   */
  get inspectorHTML () {
    return NotInspectorAble();
  }
  
  /**
   * @return {WRoot | Widget}
   */
  getRoot () {
    let widget = this;
    while (widget.parentWidget !== null && widget.parentWidget !== undefined) {
      widget = widget.parentWidget;
    }
    return widget;
  }

  /**
   * @returns {WidgetJSON}
   */
  save () {
    return {
      type: "Widget"
    }
  }
  
  focus () {
    console.log("You should probably add a way that will show that the element is focused. Like it will appear in inspector and maybe the caret will be set to the end. Just sain.");
  }

  /**
   * @returns {WidgetJSON[]}
   */
  saveChildren () {
    return this.children
      .reduce((accumulator, current) => {
        accumulator.push(current.save());
        return accumulator;
      }, []);
  }
  
  removeInspectHandler () {
    if (window.inspect === undefined) return;
    
    this.rootElement.removeEventListener("click", this.inspectHandler);
  }
  
  removeMargin() {
    this.rootElement.classList.remove("margin");
  }

  remove () {
    this.parentWidget.removeWidget(this);
  }
  
  /**
   * @param {Widget} replacement
   */
  replaceSelf (replacement) {
    this.parentWidget.replaceWidget(this, replacement);
  }

  /**
   * @param {Widget} widget 
   */
  removeWidget (widget) {
    widget.rootElement.remove();
    this.children.splice(this.children.indexOf(widget), 1);
  }
  
  /**
   * @param {Widget} find
   * @param {Widget} replacement
   */
  replaceWidget (find, replacement) {
    find.rootElement.parentElement.insertBefore(replacement.rootElement, find.rootElement);
    find.rootElement.remove();
    this.children.splice(this.children.indexOf(find), 1, replacement);
  }
  
  /**
   * @param {Widget | Promise<Widget>} widget
   * @param {boolean} doAppendToRootElement
   */
  #append (widget, doAppendToRootElement = true) {
    if (this.childSupport === "none" || this.children.length === this.childSupport) {
      console.warn("Trying to add child to parent, who does not accept any more children.");
      return;
    }
  
    this.children.push(widget);
    
    if (doAppendToRootElement) {
      this.rootElement.appendChild(widget.rootElement);
    }
  }
  
  /**
   * @param {Widget | Promise<Widget>} widget
   * @param {boolean} doAppendToRootElement
   */
  appendWidget (widget, doAppendToRootElement = true) {
    if (widget instanceof Promise) {
      return new Promise(resolve => {
        widget.then(w => {
          this.#append(w);
          resolve();
        });
      });
    }
    
    this.#append(widget);
  }

  appendEditGui () {
    this.rootElement.style.position = "relative";
    this.rootElement.classList.add("edit");
    
    
    this.rootElement.appendChild(
      Div("gui edit-controls", [
        Button(__, "+", () => {
          this.parentWidget.placeCommandBlock(this);
        }, {
          attributes: {
            contenteditable: false
          }
        }),
        Button(__, "::", evt => {}, {
          attributes: {
            contenteditable: false
          }
        }),
        // Button(__, "edit", evt => {}, {
        //   attributes: {
        //     contenteditable: false
        //   }
        // }),
        Button(__, "x", () => this.remove(), {
          attributes: {
            contenteditable: false
          }
        })
      ])
    );
  }

  /**
   * @param {Widget} after 
   */
  placeCommandBlock (after) {
    if (document?.widgetElement.editable !== true) return;
  
    const indexOfAfter = this.children.indexOf(after);
    
    if (this.children[indexOfAfter + 1] instanceof WCommand) return;
    
    const cmd = WCommand.default(this);
    this.children.splice(indexOfAfter + 1, 0, cmd);
    
    if (indexOfAfter + 2 === this.children.length) {
      this.rootElement.appendChild(cmd.rootElement);
    } else {
      this.rootElement.insertBefore(cmd.rootElement, this.children[indexOfAfter + 2].rootElement);
    }

    cmd.rootElement.focus();
  }
}




class ContainerWidget extends Widget {
  /**
   * @typedef {"multiple" | number} ContainerWidgetChildSupport
   */

  #doRemoveDefaultCommand = false;

  /**
   * @param {HTMLElement} root 
   * @param {Widget} parent 
   * @param {boolean} editable
   */
  constructor (root, parent, editable = false) {
    super(root, parent, editable);
    
    if (editable) {
      this.appendWidget(WCommand.default(this));
      this.#doRemoveDefaultCommand = true;
    }
  }
  
  /**
   * @override
   * @param {ContainerWidgetChildSupport} value
   */
  set childSupport (value) {
    if (value === "none" || value <= 0) {
      console.error("ContainerWidget must accept children.");
      return;
    }

    super.childSupport = value;
  }

  /**
   * @override
   * @param {Widget} widget 
   */
  removeWidget (widget) {
    // when only children = command block do NOT remove
    if (widget instanceof WCommand && this.children.length === 1) return;
    
    super.removeWidget(widget);
    
    // when children (Widget) length = 0 place new command block
    if (this.children.length === 0) {
      return this.appendWidget(WCommand.default(this));
    }
  }

  /**
   * @param {Widget} widget 
   */
  appendWidget (widget) {
    if (this.#doRemoveDefaultCommand === true) {
      super.removeWidget(this.children[0]);
      this.#doRemoveDefaultCommand = false;
    }

    return super.appendWidget(widget);
  }
}




class WidgetRegistry {
  /**
   * @type {Object.<string, typeof Widget>}
   */
  #map = {};
  /**
   * @callback WidgetRegistryCallback
   * @param {typeof Widget} clazz
   */
  /**
   * @type {Object.<string, WidgetRegistryCallback[] | undefined>}
   */
  #callbacks = {};
  
  #setoff (className) {
    if (this.#callbacks[className] === undefined) return;
    
    for (const callback of this.#callbacks[className]) {
      callback(this.#map[className]);
    }
  }
  
  /**
   * @param {string} className
   * @param {typeof Widget} constructor
   */
  define (className, constructor) {
    this.#map[className] = constructor;
    this.#setoff(className);
  }
  
  
  /**
   * @param {string} className
   */
  get (className) {
    return this.#map[className];
  }
  
  
  /**
   * @param {string} className
   */
  exists (className) {
    return this.#map[className] !== undefined;
  }
  
  
  /**
   * @param {...string} classNames
   * @return {Promise<Awaited<void>[]>}
   */
  request (...classNames) {
    let requestClassNames = "";
    const widgetPromises = [];
  
    for (const className of classNames) {
      if (this.exists(className)) continue;
  
      requestClassNames += className + ",";
      widgetPromises.push(
        new Promise(resolve => this.on(className, resolve))
      );
    }
    
    if (requestClassNames === "") {
      return Promise.resolve();
    }
    
    const subtrahend = Object.keys(this.#map);
    const query = "?widgets=" + requestClassNames.substring(0, requestClassNames.length - 1) + (subtrahend.length !== 0
      ? `&subtrahend=${subtrahend.join(",")}`
      : "");
    
    AJAX.get("/bundler/css/" + query, TextHandler(), AJAX.CORS_OPTIONS, AJAX.SERVER_HOME)
      .then(text => {
        document.head.appendChild(
          Component("style", __, HTML(text))
        );
      });
    AJAX.get("/bundler/js/" + query, TextHandler(), AJAX.CORS_OPTIONS, AJAX.SERVER_HOME)
      .then(text => {
        document.head.appendChild(
          Component("script", __, HTML(text))
        );
      });
    
    return Promise.all(widgetPromises);
  }
  
  
  
  /**
   * @param {string} className
   * @param {WidgetRegistryCallback} callback
   */
  on (className, callback) {
    if (this.#map[className] !== undefined) {
      callback(this.#map[className]);
      return;
    }
    
    if (this.#callbacks[className] === undefined) {
      this.#callbacks[className] = [];
    }
    
    this.#callbacks[className].push(callback);
  }
}
const widgets = new WidgetRegistry();



/**
 * @typedef Page
 * @property {Widget} root
 * @property {HTMLElement} inspector
 */