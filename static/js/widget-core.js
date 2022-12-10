class Inspector {
  /**
   * @typedef InspectorElement
   * @prop {"Label"|"Checkbox"|"Radio"|"Textfield"|"Textarea"|"Number"|"Select"|"HR"|"Whitespace"} type
   * @prop {string=} content
   * @prop {number=} level
   * @prop {string=} placeholder
   * @prop {(HTMLElement)=>void=} stateChangeHandler
   * @prop {{value: string, label: string}[]|string=} options
   */
  /**
   * @typedef InspectorJSON
   * @prop {boolean=} inspectorAble
   * @prop {InspectorElement[]} elements
   */


  constructor (element) {
    this.element = element;
  }


  /**
   * @param {InspectorJSON} json 
   */
  build (json) {
    if (json.inspectorAble === false) return;
  }
}


















/**
 * @typedef WidgetJSON
 * @prop {string} type
 * @prop {Object.<string, string>=} style
 * @prop {WidgetJSON[]=} children
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
   */
  constructor (root, parent) {
    this.rootElement = root;
    this.rootElement.widget = this;
    this.rootElement.classList.add("widget");

    this.parentWidget = parent;

    /** @type {Widget[]} */
    this.children = [];
  }

  /**
   * @returns {Widget}
   */
  static default (parent) {
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
   * @returns {InspectorJSON}
   */
  get inspectorHTML () {
    return {
      elements: [
        {
          type: "Label",
          content: "Widget"
        }
      ]
    }
  }

  /**
   * @returns {WidgetJSON}
   */
  save () {
    return {
      type: "Widget"
    }
  }

  /**
   * @returns {WidgetJSON[]}
   */
  saveChildren () {
    return this.children.reduce((arr, child) => arr.push(child.save), []);
  }

  remove () {
    this.parentWidget.removeWidget(this);
  }

  /**
   * @param {Widget} widget 
   */
  removeWidget (widget) {
    widget.rootElement.remove();
    this.children.splice(this.children.indexOf(widget), 1);
  }
  
  /**
   * @param {Widget} widget 
   */
  appendWidget (widget) {
    if (this.childSupport === "none" || this.children.length === this.childSupport) {
      console.warn("Trying to add child to parent, who does not accept any more children.");
      return;
    }

    this.children.push(widget);
    this.rootElement.appendChild(widget.rootElement);
  }

  appendEditGui () {
    this.rootElement.style.position = "relative";
    this.rootElement.classList.add("edit");
    this.rootElement.appendChild(html({
      className: "edit-gui",
      content: [{
        name: "button",
        content: "+",
        attributes: {
          contenteditable: false
        },
        listeners: {
          click: evt => {
            this.parentWidget.placeCommandBlock(this);
          }
        }
      }, {
        name: "button",
        content: "::",
        attributes: {
          contenteditable: false
        }
      }, {
        name: "button",
        content: "edit",
        attributes: {
          contenteditable: false
        }
      }, {
        name: "button",
        content: "x",
        attributes: {
          contenteditable: false
        },
        listeners: {
          click: evt => {
            this.remove();
          }
        }
      }]
    }));
  }

  /**
   * @param {Widget} after 
   */
  placeCommandBlock (after) {
    if (document?.widgetElement.editable === true) {
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
}




class ContainerWidget extends Widget {
  /**
   * @typedef {"multiple" | number} ContainerWidgetChildSupport
   */

  #doRemoveDefaultCommand = false;

  /**
   * @param {HTMLElement} root 
   * @param {Widget} parent 
   */
  constructor (root, parent) {
    super(root, parent);
    this.appendWidget(WCommand.default(this));
    this.#doRemoveDefaultCommand = true;
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
      this.appendWidget(WCommand.default(this));
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

    super.appendWidget(widget);
  }
}




/**
 * @typedef Page
 * @prop {Widget} root
 * @prop {HTMLElement} inspector
 */