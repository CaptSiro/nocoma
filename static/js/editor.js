const viewportMount = $(".viewport-mount");
let switcher = false;
$(".toggle-viewport").addEventListener("pointerdown", () => {
  switcher = !switcher;
  viewport.classList.toggle("mobile", switcher);
  viewport.classList.toggle("viewport-smartphone", switcher);
  
  window.dispatchEvent(new Event("resize"));
});


/**
 * @typedef ViewportDimensions
 * @property {number} width
 * @property {number} height
 * @property {number} convertedWidth
 * @property {number} convertedHeight
 *
 * @callback ViewportListener
 * @param {ViewportDimensions} dimensions
 *
 * @type {ViewportListener[]}
 */
const viewportListeners = [];
const inspectorRoot = $(".table > .inspector");
const nav = $("nav");
const viewport = $("#viewport");
window.addEventListener("resize", () => {
  const width = window.innerWidth - inspectorRoot.getBoundingClientRect().width;
  const height = window.innerHeight - nav.getBoundingClientRect().height;
  const isInMobileMode = viewport.classList.contains("mobile");
  
  const viewportDimensions = {
    width,
    height,
    convertedWidth: isInMobileMode
      ? height / 16 * 9
      : height / 9 * 16,
    convertedHeight: isInMobileMode
      ? width / 9 * 16
      : width / 16 * 9,
  }
  
  for (const viewportListener of viewportListeners) {
    viewportListener(viewportDimensions)
  }
});

onViewportResize(dimensions => {
  viewport.style.height = `min(${dimensions.convertedHeight}px, 100%)`;
  viewport.style.width = `min(${dimensions.convertedWidth}px, 100%)`;
});

/**
 * @param {ViewportListener} callback
 */
function onViewportResize (callback) {
  viewportListeners.push(callback);
}



$$(".id > img").forEach(img => {
  img.addEventListener("pointerdown", evt => {
    img.closest(".w-category").classList.toggle("expanded");
  });
});






//? dropdown
const settingItems = $$('div.settings-bar > div.settings-drop');
const HelperDropdown = {
  drop: false,
  /** @type {HTMLElement} */
  current: null,

  /**
   * @param {HTMLElement} element 
   * @returns 
   */
  changeCurrent: function(element) {
    if (element == null) return;

    const locked = [];
    if (this.current != null) {
      this.current.classList.remove('drop', 'locked');
      locked.push(this.current.classList);
    }
    this.current = element;
    this.drop = true;
    this.current.classList.add('drop', 'lock');

    locked.push(this.current.classList);
    setTimeout(() => {
      locked.forEach(l => l.remove("lock"));
    }, 250);
  },

  /**
   * @param {HTMLElement} item 
   * @returns {boolean}
   */
  isPartOfDrop: function(item) {
    return (item.closest(".drop.settings-drop") !== null);
  },

  /**
   * @param {HTMLElement} child 
   * @returns {HTMLElement}
   */
  findSettingsItem: function(child) {
    let settingItem = null;
    let next = child;

    while (next != null) {
      if (next.classList.contains('settings-drop')) {
        settingItem = next;
      }
      next = next.parentElement;
    }

    return settingItem;
  }
}
function stopDropdown () {
  settingItems.forEach(e => e.classList.remove('drop'))
  HelperDropdown.drop = false;
}
window.addEventListener('click', evt => {
  if (HelperDropdown.isPartOfDrop(evt.target)) return;
  stopDropdown();
});
settingItems.forEach(e => {
  e.addEventListener('click', evt => {
    HelperDropdown.changeCurrent(HelperDropdown.findSettingsItem(evt.target));
  });
  e.addEventListener('mouseenter', evt => {
    if (HelperDropdown.drop === true && !e.classList.contains("drop")) {
      HelperDropdown.changeCurrent(HelperDropdown.findSettingsItem(evt.target));
    }
  });
});
$("#edit-website-properties").addEventListener("click", evt => {
  window.rootWidget?.rootElement.click();
  stopDropdown();
  evt.stopImmediatePropagation();
});






//? controls resizer
$$(".controls .resize-divider").forEach(rd => {
  rd.addEventListener("pointerdown", evt => {
    rd.setPointerCapture(evt.pointerId);

    const pmove = pmoveEvt => {
      const parentHeight = rd.parentElement.clientHeight;
      const rectPrev = rd.previousElementSibling.getBoundingClientRect();
      const cursorTop = pmoveEvt.clientY;
      
      const prev = parentHeight + (cursorTop - (rectPrev.top + parentHeight));
      const next = parentHeight - prev;

      if (prev > 50 && next > 50) {
        rd.previousElementSibling.style.height = prev + "px";
        rd.nextElementSibling.style.height = next + "px";
      }
    };

    rd.addEventListener("pointermove", pmove);

    rd.addEventListener("pointerup", _ => {
      rd.removeEventListener("pointermove", pmove);
    });
  });
});












//? widget menu
const widgetSelect = $("#widget-select-mount");
/** @type {HTMLElement} */
let selectedWidget = undefined;
let isInSearchMode = false;

/**
 * @param {boolean} isInSearch
 */
function setSearchMode (isInSearch) {
  isInSearchMode = isInSearch;
  widgetSelect.classList.toggle("search-mode", isInSearch);
}

/**
 * @param {boolean} direction true => Up; false => Down
 */
function moveSelection (direction) {
  if (selectedWidget === undefined) {
    const widgetPool = isInSearchMode
      ? widgetSelect.querySelectorAll(".widget-option.search-satisfactory")
      : widgetSelect.querySelectorAll(".widget-option")
    
    selectedWidget = widgetPool[!direction ? 0 : (widgetPool.length - 1)];
    selectedWidget.classList.add("selected");
    return;
  }
  
  selectedWidget.classList.remove("selected");
  let selectionPool = Array.from(widgetSelect.querySelectorAll(".widget-option"));
  if (direction) {
    selectionPool = selectionPool.reverse();
  }
  
  let pointer = selectionPool.indexOf(selectedWidget);
  
  do {
    pointer++;
    if (pointer === selectionPool.length) {
      pointer = 0;
    }
    
    if (isInSearchMode === false || selectionPool[pointer].classList.contains("search-satisfactory")) {
      selectedWidget = selectionPool[pointer];
      break;
    }
  } while (selectionPool[pointer] !== selectedWidget);
  
  selectedWidget.classList.add("selected");
}


AJAX.get("/bundler/resource/*", JSONHandlerSync(json => {
  /** @type {Map<string, { properties: { category: string, label: string, class: string, searchIndex: string }, files: { icon: string } }[]>} */
  const grouped = Array.from(json)
    .filter(resource => resource.properties.category !== "Hidden")
    .reduce((map, resource) => {
      resource.properties.searchIndex = resource.properties.category + "_" + resource.properties.label;
      
      if (map.has(resource.properties.category)) {
        map.get(resource.properties.category).push(resource);
      } else {
        map.set(resource.properties.category, [resource]);
      }

      return map;
    }, new Map())
  
  widgetSelect.textContent = "";
  const filenameRegex = /^.*[\\\/]/;
  for (const key of Array.from(grouped.keys()).sort()) {
    
    widgetSelect.appendChild(
      Div("widget-category", [
        Div("label",
          Heading(3, __, key)
        ),
        Div("content", grouped.get(key).map(resource =>
          Div("widget-option", [
            Img(resource.files.icon, resource.files.icon.replace(filenameRegex, "")),
            Span(__, resource.properties.label)
          ], {
            listeners: {
              click: function () {
                console.log(widgets.get(resource.properties.class).default(null));
              },
              mouseover: function () {
                widgetSelect.querySelectorAll(".widget-option").forEach(w => w.classList.remove("selected"));
                selectedWidget = this;
                this.classList.add("selected");
              }
            },
            modify: widgetElement => {
              widgetElement.dataset.search = resource.properties.searchIndex;
              widgetElement.dataset.class = resource.properties.class;
            },
          })
        ))
      ])
    );
  }
}));
window.addEventListener("load", async () => {
  const dataElement = $("#page-data");
  const root = await WRoot.build(JSON.parse(dataElement.textContent), null, true);
  
  dataElement.remove();
  window.rootWidget = root;
  document.widgetElement = root;
  document.querySelector("#viewport").appendChild(root.rootElement);
  root.rootElement.click();
});


/**
 * @param {HTMLElement} to
 */
function moveWidgetSelect (to) {
  to.scrollIntoView();
  const toBoundingBox = to.getBoundingClientRect();
  const mountBoundingBox = viewportMount.getBoundingClientRect();
  const selectBoundingBox = widgetSelect.getBoundingClientRect();
  
  let left = toBoundingBox.left;
  if (left + selectBoundingBox.width > mountBoundingBox.width) {
    left = mountBoundingBox.width - selectBoundingBox.width;
  }
  left /= (mountBoundingBox.width / 100);
  
  let top = toBoundingBox.top + toBoundingBox.height;
  if (top + selectBoundingBox.height > mountBoundingBox.height) {
    top = toBoundingBox.top - selectBoundingBox.height;
  }
  top -= mountBoundingBox.top;
  top /= (mountBoundingBox.height / 100);
  
  widgetSelect.style.left = left + "%";
  widgetSelect.style.top = top + "%";
  
  widgetSelect.style.visibility = "visible";
}





const fileSelectModal = $("#file-select");
const filesModal = fileSelectModal.querySelector(".files");
const filesError = fileSelectModal.querySelector(".error-modal");
const filesModalInfiniteScroller = new InfiniteScroller(filesModal, async (index) => {
  const files = await AJAX.get(`/file/${index}/?type=${fileSelectModal.dataset.fileType}`, JSONHandler());
  
  let element;
  for (const file of files) {
    const fileURL = `${AJAX.SERVER_HOME}/file/${webpage.src}/${file.src}${file.extension}`;
    element = Div("item", [
      FileIcon(file.mimeContentType, { "image": fileURL }),
      Paragraph(__, String(file.basename + file.extension))
    ], {
      listeners: {
        click: evt => {
          if (fileSelectModal.dataset.multiple != true) {
            for (let child of evt.currentTarget.parentElement.children) {
              child.classList.remove("selected");
              child.dataset.selected = "false";
            }
          }
          evt.currentTarget.dataset.selected = String(!(evt.currentTarget.dataset.selected ?? false));
          evt.currentTarget.classList.toggle("selected", !!evt.currentTarget.dataset.selected);
        }
      },
      attributes: {
        title: String(file.basename + file.extension),
        "data-url": fileURL,
        "data-name": file.basename + file.extension,
        "data-server": file.src + file.extension,
        "data-src": file.src,
      }
    });
    filesModal.appendChild(element);
  }
  
  return element;
}, undefined, false);
fileSelectModal.addEventListener("fetch", () => {
  filesModalInfiniteScroller.reset();
});
fileSelectModal.querySelector("button[type=submit]").addEventListener("click", () => {
  const selected = Array.from(filesModal.children)
    .filter(file => file.classList.contains("selected"))
    .map(file => ({
      url: file.dataset.url,
      name: file.dataset.name,
      src: file.dataset.src,
      serverName: file.dataset.server
    }));
  
  if (selected.length === 0) {
    return;
  }
  
  fileSelectModal.dispatchEvent(new CustomEvent("submit", { detail: selected }));
  clearWindows();
});
fileSelectModal.querySelector("#file-upload-input").addEventListener("change", async evt => {
  const body = new FormData();
  for (const file of evt.target.files) {
    body.append("uploaded[]", file);
  }
  
  const files = await AJAX.post("/file/collect", JSONHandler(), { body });
  
  if (files.error) {
    filesError.textContent = files.error;
    filesError.classList.add("show");
    return;
  }
  
  filesError.classList.remove("show");
  filesModalInfiniteScroller.reset();
});








//* inspector
inspectorRoot.textContent = "";

const methods = () => false

inspectorRoot.append(
  CheckboxInspector(false, methods),
  CheckboxInspector(true, methods, "Hello"),
  TitleInspector("Hey i m a title"),
  RadioGroupInspector(methods, [{
    text: "Male",
    value: "male"
  }, {
    text: "Female",
    value: "female"
  }, {
    text: "Other",
    value: "other",
  }], "Gender"),
  HRInspector(),
  TextFieldInspector(__, methods, "Label:", "MY next project..."),
  TextAreaInspector(__, methods),
  TextAreaInspector("Hello there!", methods, "My area"),
  TextAreaInspector("Obi van Keno bi", methods, "My area", "Message"),
  NumberInspector(50, methods, "Age", "18", "lmaosobad"),
  NumberInspector(__, methods, "Width:", "20",
    SelectInspector(methods, [{
      text: "px",
      value: "px"
    }, {
      text: "in",
      value: "in"
    }, {
      text: "%",
      value: "%",
      selected: true
    }], __, "small")
  ),
  DateInspector(new Date("2020-01-01"), methods, "Date of upload"),
  SelectInspector(methods, [{
    text: "Male",
    value: "male"
  }, {
    text: "Female",
    value: "female",
    selected: true
  }, {
    text: "Other",
    value: "other"
  }], "My select", "x-large"),
  NotInspectorAble(),
  TextAreaInspector(__, methods),
  TextAreaInspector(__, methods),
  TextAreaInspector(__, methods),
  TextAreaInspector(__, methods),
  TextAreaInspector(__, methods),
);

let currentlyInspecting;
/**
 * @param {ComponentContent} inspectorHTML
 * @param {Widget} widget
 */
function inspect (inspectorHTML, widget) {
  currentlyInspecting = widget;
  inspectorRoot.textContent = "";
  inspectorRoot.append(...parseComponentContent(inspectorHTML));
}













document.body.addEventListener("keydown", async evt => {
  if (evt.key === "s" && evt.ctrlKey) {
    evt.preventDefault();
    evt.stopImmediatePropagation();
    await save();
  }
});
async function save () {
  const structure = window.rootWidget.save();
  
  const response = await AJAX.post("/page/" + webpage.src, JSONHandler(), {
    body: JSON.stringify({
      content: JSON.stringify(structure)
    })
  }).catch(errorResponse => {
    errorResponse.text().then(console.log);
  });
  
  if (response.error) {
    alert(response.error);
    return;
  }
  
  alert(response.message);
}





/**
 * @template S
 * @callback Setter
 * @param {S} value
 * @param {HTMLElement} parentElement
 * @returns {boolean | Promise<boolean>}
 */

/**
 * @param {Event} evt
 * @param {Setter<string>} setter
 * @param {string | undefined} lastValue
 * @param {HTMLElement} parent
 * @param {HTMLCollection} collection
 * @returns {(function(*): (*))|*}
 */
async function choiceChangeListener (evt, setter, lastValue, parent, collection) {
  if (await setter(evt.target.value, parent)) {
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
        change: async evt => {
          if (await setter(evt.target.checked, checkbox)) {
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
 * @param {string} className
 * @returns {HTMLElement}
 */
function TitleInspector (title, className = undefined) {
  return (
    Heading(3, "i-title" + (className !== undefined ? " " + className : ""), title)
  );
}

function HRInspector (className = undefined) {
  return (
    Div("i-hr" + (className !== undefined ? " " + className : ""))
  )
}

/**
 * @typedef KeyValuePair
 * @property {string} text
 * @property {string} value
 * @property {boolean=} selected
 */
/**
 * @param {Setter<string>} setter
 * @param {KeyValuePair[]} radios
 * @param {string} label
 * @returns {HTMLElement}
 */
function RadioGroupInspector (setter, radios, label = undefined) {
  const name = guid(true);
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
        change: async evt => {
          lastValue = await choiceChangeListener(evt, setter, lastValue, radioGroup, radioGroup.querySelectorAll(`input[name=${name}]`));
        }
      }
    })
  );
  
  return radioGroup;
}

/**
 * @param {KeyValuePair[]} options
 * @param {string} value
 * @param {string} defaultValue
 * @return {KeyValuePair[]}
 */
function selectOption (options, value, defaultValue = undefined) {
  let defaultOption;
  for (const option of options) {
    if (option.value === defaultValue) {
      defaultOption = option;
    }
    if (option.value !== value) continue;
    
    option.selected = true;
    return options;
  }
  
  defaultOption.selected = true;
  return options;
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
function LabelAndComponentInspector (className, setter, label, component, placeholder = undefined, options = undefined) {
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
 * @param {string | undefined} label
 * @param {HTMLElement} elementFor
 */
function LabelFactory (label, elementFor) {
  if (label === undefined) {
    return;
  }
  
  const id = guid(true);
  
  elementFor.id = id;
  return Label(__, label, {
    attributes: {
      for: id
    }
  });
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
  const parent = Div("i-text-area");
  
  options ||= {};
  options.listeners ||= {};
  options.listeners.blur ||= async evt => {
    if (state === evt.target.value) return;
    if (await setter(evt.target.value, parent)) {
      state = evt.target.value;
      return;
    }
    
    evt.target.value = state ?? "";
  };
  options.listeners.keydown ||= evt => {
    if (!(evt.key === "Enter" && evt.ctrlKey)) return;
    evt.target.dispatchEvent(new Event("blur"));
  }
  
  options.attributes ||= {};
  if (state) {
    options.attributes.value = state;
  }
  
  const area = Component("textarea", __, state, options);
  const labelElement = LabelFactory(label, area);
  
  if (placeholder) {
    area.setAttribute("placeholder", placeholder);
  }
  
  parent.append(...parseComponentContent([
    labelElement,
    area
  ]));
  
  return parent;
}

/**
 * @param {string | undefined} state
 * @param {Setter<string>} setter
 * @param {string} label
 * @param {string} placeholder
 * @returns {HTMLElement}
 */
function TextFieldInspector (state, setter, label = undefined, placeholder = undefined) {
  const parent = Div("i-text-field");
  const options = {
    listeners: {
      blur: async evt => {
        if (state === evt.target.value) return;
        if (await setter(evt.target.value, parent)) {
          state = evt.target.value;
          return;
        }
        
        evt.target.value = state ?? "";
      },
      keydown: evt => {
        if (evt.key !== "Enter") return;
        evt.target.dispatchEvent(new Event("blur"));
      }
    },
    attributes: {}
  };
  
  if (state !== undefined) {
    options.attributes.value = state;
  }
  
  const textField = Input("text", __, options);
  const labelElement = LabelFactory(label, textField);
  
  if (placeholder) {
    textField.setAttribute("placeholder", placeholder);
  }
  
  parent.append(...parseComponentContent([
    labelElement,
    textField
  ]));
  
  return parent;
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
 * @param {Date | undefined} state
 * @param {Setter<Date>} setter
 * @param {string} label
 * @param {boolean} isDateTime
 * @param {string} placeholder
 * @returns {HTMLElement}
 */
function DateInspector (state, setter, label = undefined, isDateTime = false, placeholder = undefined) {
  const input = Input(isDateTime ? "datetime-local" : "date");
  input.addEventListener("change", async () => {
    if (state === input.valueAsDate) return;
    if (await setter(input.valueAsDate, input.parentElement)) {
      state = input.valueAsDate;
      return;
    }
  
    input.valueAsDate = state ?? "";
  });
  
  if (state) {
    input.valueAsDate = state;
  }
  
  return (
    LabelAndComponentInspector("i-date", setter, label, input, placeholder)
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
  
  const parent = Div("i-select dont-force" + (className !== undefined ? (" " + className) : ""));
  
  const select = (
    Component("select", __,
      options.map(option =>
        new Option(option.text, option.value, __, option?.selected)
      ), {
        listeners: {
          change: async evt => {
            lastValue = await choiceChangeListener(evt, setter, lastValue, parent, select.children);
          }
        }
      }
    )
  );
  
  parent.append(...parseComponentContent([
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
  ]))
  
  return parent;
}

/**
 * @param {string | undefined} state
 * @param {Setter<string>} setter
 * @param {string} label
 * @param {string} placeholder
 * @returns {HTMLElement}
 */
function ColorPickerInspector (state, setter, label = undefined, placeholder = undefined) {
  const options = {
    attributes: {}
  };
  
  if (state) {
    options.attributes.value = state;
  }
  
  return (
    LabelAndComponentInspector("i-date", setter, label, Input("color", __, options), placeholder)
  );
}

function NotInspectorAble () {
  // return (
  //   TitleInspector("This element cannot be changed")
  // );
  return undefined;
}







class ColorPicker {
  #rootElement;
  get rootElement () {
    return this.#rootElement;
  }
  #display;
  #red;
  #green;
  #blue;
  #alpha;
  #old;
  #new;
  
  constructor (inline = false, cancelAction = () => {}, pickAction = () => {}) {
    this.#rootElement = ColorPicker.createColorPicker(inline);
    
    this.#red = this.#rootElement.querySelector(".color-picker-r");
    this.#red.addEventListener("input", this.onRGBChange("red"));
    
    this.#green = this.#rootElement.querySelector(".color-picker-g");
    this.#green.addEventListener("input", this.onRGBChange("green"));
    
    this.#blue = this.#rootElement.querySelector(".color-picker-b");
    this.#blue.addEventListener("input", this.onRGBChange("blue"));
    
    this.#alpha = this.#rootElement.querySelector(".color-picker-a");
    this.#alpha.addEventListener("input", this.onRGBChange("alpha"));
    
    this.#display = this.#rootElement.querySelector(".format");
    this.#display.addEventListener("input", evt => {
      const color = this.parseColor(evt.target.value);
      if (color === null) {
        this.#display.classList.add("invalid");
        return;
      }
      
      this.#display.classList.remove("invalid");
      this.setNewColor(color);
    });
    
    this.#old = this.#rootElement.querySelector(".old");
    this.#new = this.#rootElement.querySelector(".new");
    
    this.#rootElement.querySelector(".cancel")?.addEventListener("click", cancelAction);
    this.#rootElement.querySelector(".pick")?.addEventListener("click", pickAction);
  }
  
  static createColorPicker (inline = false) {
    const guids = Array(5).fill(null).map(() => guid(true));
    
    return (
      Div("color-picker", [
        OptionalComponent(inline === false,
          Div("showcase", [
            Div("transparent"),
            Div("old"),
            Div("new"),
          ])
        ),
        Div("sliders", [
          Div("row", [
            Label(__, "R:", { attributes: { for: guids[0] } }),
            Input("range", "color-picker-r", {
              attributes: {
                min: "0",
                max: "255",
                value: "0",
                id: guids[0]
              }
            })
          ]),
          Div("row", [
            Label(__, "G:", { attributes: { for: guids[1] } }),
            Input("range", "color-picker-g", {
              attributes: {
                min: "0",
                max: "255",
                value: "0",
                id: guids[1]
              }
            })
          ]),
          Div("row", [
            Label(__, "B:", { attributes: { for: guids[2] } }),
            Input("range", "color-picker-b", {
              attributes: {
                min: "0",
                max: "255",
                value: "0",
                id: guids[2]
              }
            })
          ]),
          Div("row", [
            Label(__, "A:", { attributes: { for: guids[3] } }),
            Input("range", "color-picker-a", {
              attributes: {
                min: "0",
                max: "1",
                step: "0.01",
                value: "0",
                id: guids[3]
              }
            })
          ])
        ]),
        Div("row", [
          Label("format-label", "", { attributes: { for: guids[4] } }),
          Input("text", "format", { attributes: { id: guids[4] } })
        ]),
        OptionalComponent(inline === false,
          Div("controls", [
            Button("button-like-main cancel", "Cancel"),
            Button("button-like-main pick", "Pick"),
          ])
        )
      ])
    );
  }
  
  /**
   * @param {Color} color
   */
  static toHEX (color) {
    return "#" + [
      color.red.toString(16),
      color.green.toString(16),
      color.blue.toString(16),
      Math.round(color.alpha * 255).toString(16)
    ]
      .map(channel => (channel.length === 1 ? ("0" + channel) : channel))
      .join("");
  }
  
  setChannel (channel, value) {
    this.#rootElement.style.setProperty("--" + channel, value);
  }
  onRGBChange (channel) {
    return evt => {
      this.setChannel(channel, evt.target.value);
      this.displayCurrentColor();
      this.#rootElement.dispatchEvent(new CustomEvent("pick", { detail: this.getCurrentColor() }))
    }
  }
  
  /**
   * @typedef Color
   * @property {number} red
   * @property {number} green
   * @property {number} blue
   * @property {number} alpha
   */
  /**
   * @param {string} colorFormat
   * @return {Color | null}
   */
  parseColor (colorFormat) {
    let values = /^rgba?\((25[0-5]|2[0-4][0-9]|1?[0-9]{1,2}) ?, ?(25[0-5]|2[0-4][0-9]|1?[0-9]{1,2}) ?, ?(25[0-5]|2[0-4][0-9]|1?[0-9]{1,2}) ?,? ?(1|0|0\.[0-9]+)?\)$/.exec(colorFormat);
    if (values !== null) {
      return {
        red: +values[1],
        green: +values[2],
        blue: +values[3],
        alpha: +(values[4] ?? 1),
      }
    }
    
    switch (colorFormat.length) {
      case 4:
        values = /^#([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])$/.exec(colorFormat);
        if (values === null) return null;
        return {
          red: parseInt(values[1].repeat(2), 16),
          green: parseInt(values[2].repeat(2), 16),
          blue: parseInt(values[3].repeat(2), 16),
          alpha: 1
        }
      case 7:
        values = /^#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})$/.exec(colorFormat);
        if (values === null) return null;
        return {
          red: parseInt(values[1], 16),
          green: parseInt(values[2], 16),
          blue: parseInt(values[3], 16),
          alpha: 1
        }
      case 9:
        values = /^#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})$/.exec(colorFormat);
        if (values === null) return null;
        return {
          red: parseInt(values[1], 16),
          green: parseInt(values[2], 16),
          blue: parseInt(values[3], 16),
          alpha: parseInt(values[4], 16) / 255,
        }
      default:
        return null;
    }
  }
  
  setOldColor (colorFormat) {
    const color = this.parseColor(colorFormat);
    if (color === null) {
      throw "Unknown color format. Currently supported: RGB, RGBA, HEX(3 letters), HEX (6 letters), HEX + alpha (8 letters)";
    }
    
    this.#old.style.backgroundColor = `rgba(${color.red}, ${color.green}, ${color.blue}, ${color.alpha})`;
    this.setNewColor(color);
    this.displayCurrentColor();
  }
  
  setNewFromFormat (colorFormat) {
    const color = this.parseColor(colorFormat);
    if (color === null) {
      throw "Unknown color format. Currently supported: RGB, RGBA, HEX(3 letters), HEX (6 letters), HEX + alpha (8 letters)";
    }
    
    this.setNewColor(color);
    this.displayCurrentColor();
  }
  
  setNewColor (color) {
    this.#red.value = color.red;
    this.setChannel("red", color.red);
    
    this.#green.value = color.green;
    this.setChannel("green", color.green);
    
    this.#blue.value = color.blue;
    this.setChannel("blue", color.blue);
    
    this.#alpha.value = color.alpha;
    this.setChannel("alpha", color.alpha);
  
    this.#rootElement.dispatchEvent(new CustomEvent("pick", { detail: color }))
  }
  
  getCurrentColor () {
    return {
      red: Number(this.#rootElement.style.getPropertyValue("--red")),
      green: Number(this.#rootElement.style.getPropertyValue("--green")),
      blue: Number(this.#rootElement.style.getPropertyValue("--blue")),
      alpha: Number(this.#rootElement.style.getPropertyValue("--alpha")),
    }
  }
  
  displayCurrentColor (color = undefined) {
    this.#display.value = ColorPicker.toHEX(color ?? this.getCurrentColor())
  }
}