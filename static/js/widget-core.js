/**
 * @template S
 * @callback Setter
 * @param {S} value
 * @returns {S} Value that have been set. To signal error: return the same value and handle error in the setter method
 */

/**
 * @param {Event} evt
 * @param {Setter<string>} setter
 * @param {string | undefined} lastValue
 * @param {HTMLCollection} collection
 * @returns {(function(*): (*))|*}
 */
function choiceChangeListener (evt, setter, lastValue, collection) {
  if (setter(evt.target.value)) {
    return evt.target.value;
  }
  
  if (collection.length === 0) {
    return lastValue;
  }
  
  const attribute = collection[0].tagName === "OPTION"
    ? "selected"
    : "checked"

  for (const element of collection) {
    if (element.value !== lastValue) {
      element[attribute] = false;
      continue;
    }
  
    element[attribute] = true;
  }
  
  return lastValue;
}


//* basic inspector components
/**
 * @param {boolean} state
 * @param {Setter<boolean>} setter
 * @param {string} label
 * @returns {HTMLElement}
 */
function CheckboxInspector (state, setter, label = "") {
  const checkbox = (
    Checkbox(label, "i-checkbox", __, {
      listeners: {
        change: evt => {
          if (setter(evt.target.checked)) {
            return;
          }
          
          evt.target.checked = !evt.target.checked;
        }
      }
    })
  );
  
  if (state) {
    checkbox.querySelector("input").checked = true;
  }
  
  return checkbox;
}

/**
 * @param {string} title
 * @returns {HTMLElement}
 */
function TitleInspector (title) {
  return (
    Heading(3, "i-title", title)
  );
}

/**
 * @typedef KeyValuePair
 * @property {string} text
 * @property {string} value
 * @property {boolean} selected
 */
/**
 * @param {Setter<string>} setter
 * @param {KeyValuePair[]} radios
 * @param {string} label
 * @returns {HTMLElement}
 */
function RadioGroupInspector (setter, radios, label = undefined) {
  const name = guid();
  let lastValue = radios
    .reduce(
      (last, current) =>
        current.selected ? current.value : last,
      undefined
    );

  const radioGroup =  (
    Div("i-radio-group", [
      OptionalComponent(label !== undefined,
        Span(__, label)
      ),
      ...radios.map(radio => {
        return (
          Radio(radio.text, radio.value, name, __,
            radio.selected !== undefined
              ? {
                attributes: {
                  checked: radio.selected
                }
              }
              : undefined
          )
        )
      })
    ], {
      listeners: {
        change: evt => {
          lastValue = choiceChangeListener(evt, setter, lastValue, radioGroup.querySelectorAll(`input[name=${name}]`));
        }
      }
    })
  );
  
  return radioGroup;
}

/**
 * @template S
 * @param {string} className
 * @param {Setter<S>} setter
 * @param {string} label
 * @param {HTMLElement} component
 * @param {string | undefined} placeholder
 * @param {ComponentOptions} options
 * @returns {HTMLElement}
 */
function LabelAndComponentInspector (className, setter, label, component, placeholder, options = undefined) {
  const id = guid(true);
  
  component.id = id;
  if (placeholder) {
    component.setAttribute("placeholder", placeholder);
  }
  
  return (
    Div(className, [
      OptionalComponent(label !== undefined,
        Label(__, label, {
          attributes: {
            for: id
          }
        })
      ),
      component
    ])
  );
}

/**
 * @param {string | undefined} state
 * @param {Setter<string>} setter
 * @param {string} label
 * @param {string} placeholder
 * @param {ComponentOptions} options
 * @returns {HTMLElement}
 */
function TextAreaInspector (state, setter, label = undefined, placeholder = undefined, options = undefined) {
  if (!options) options = {};
  if (!options.listeners) options.listeners = {};
  if (!options.listeners.blur) options.listeners.blur = evt => console.log(evt.target.value);
  
  return (
    LabelAndComponentInspector("i-text-area", setter, label, Component("textarea", __, state, options), placeholder)
  );
}

/**
 * @param {string | undefined} state
 * @param {Setter<string>} setter
 * @param {string} label
 * @param {string} placeholder
 * @returns {HTMLElement}
 */
function TextFieldInspector (state, setter, label = undefined, placeholder = undefined) {
  const options = {
    attributes: {}
  };

  if (state !== undefined) {
    options.attributes.value = state;
  }
  
  return (
    LabelAndComponentInspector("i-text-field", setter, label, Input("text", __, options), placeholder)
  );
}

/**
 * @param {number | undefined} state
 * @param {Setter<string | number>} setter
 * @param {string} label
 * @param {string} placeholder
 * @param {ComponentContent} measurement
 * @returns {HTMLElement}
 */
function NumberInspector (state, setter, label = undefined, placeholder = undefined, measurement = undefined) {
  const id = guid(true);
  const options = {
    attributes: { id }
  };
  
  if (placeholder) {
    options.attributes.placeholder = placeholder;
  }
  
  if (state) {
    options.attributes.value = state;
  }
  
  return (
    Div("i-number", [
      OptionalComponent(label !== undefined,
        Label(__, label, {
          attributes: {
            for: id
          }
        })
      ),
      Input("number", __, options),
      typeof measurement === "string"
        ? Span(__, measurement)
        : measurement
    ])
  );
}

//TODO: create custom date picker
/**
 * @param {string | undefined} state
 * @param {Setter<string>} setter
 * @param {string} label
 * @param {string} placeholder
 * @returns {HTMLElement}
 */
function DateInspector (state, setter, label = undefined, placeholder = undefined) {
  const options = {
    attributes: {}
  };
  
  if (state) {
    options.attributes.value = state;
  }
  
  return (
    LabelAndComponentInspector("i-date", setter, label, Input("date", __, options), placeholder)
  );
}

/**
 * @param {Setter<string>} setter
 * @param {KeyValuePair[]} options
 * @param {string} label
 * @param {string} className
 * @returns {HTMLElement}
 */
function SelectInspector (setter, options, label = undefined, className = undefined) {
  const id = guid(true);
  let lastValue = options
    .reduce(
      (last, current) =>
        current.selected ? current.value : last,
      undefined
    );
  
  const select = (
    Component("select", __,
      options.map(option =>
        new Option(option.text, option.value, __, option?.selected)
      ), {
        listeners: {
          change: evt => {
            lastValue = choiceChangeListener(evt, setter, lastValue, select.children);
          }
        }
      }
    )
  );
  
  return (
    Div("i-select dont-force" + (className !== undefined ? (" " + className) : ""), [
      OptionalComponent(label !== undefined,
        Label(__, label, {
          attributes: {
            for: id
          }
        })
      ),
      Div("select-container",
        select
      ),
    ])
  );
}

function NotInspectorAble () {
  return (
    TitleInspector("This element cannot be changed")
  );
}

















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
   * @param {boolean} removeDefaultMargin
   */
  constructor (root, parent, removeDefaultMargin = false) {
    this.rootElement = root;
    this.rootElement.widget = this;
    this.rootElement.classList.add("widget");
  
    if (removeDefaultMargin !== true) {
      this.rootElement.classList.add("margin");
    }

    this.parentWidget = parent;

    /** @type {Widget[]} */
    this.children = [];
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
   */
  #append (widget) {
    if (this.childSupport === "none" || this.children.length === this.childSupport) {
      console.warn("Trying to add child to parent, who does not accept any more children.");
      return;
    }
  
    this.children.push(widget);
    this.rootElement.appendChild(widget.rootElement);
  }
  
  /**
   * @param {Widget | Promise<Widget>} widget
   */
  appendWidget (widget) {
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
        Button(__, "+", () => this.parentWidget.placeCommandBlock(this), {
          attributes: {
            contenteditable: false
          }
        }),
        Button(__, "::", evt => {}, {
          attributes: {
            contenteditable: false
          }
        }),
        Button(__, "edit", evt => {}, {
          attributes: {
            contenteditable: false
          }
        }),
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
   * @param {boolean} removeDefaultMargin
   */
  constructor (root, parent, editable = false, removeDefaultMargin = false) {
    super(root, parent, removeDefaultMargin);
    
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