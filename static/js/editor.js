const viewport = $("#viewport");
const viewportMount = $(".viewport-mount");
let switcher = false;
$(".toggle-viewport").addEventListener("pointerdown", evt => {
  switcher = !switcher;
  viewport.classList.toggle("mobile", switcher);
  viewport.classList.toggle("viewport-smartphone", switcher);

  if (viewport.classList.contains("mobile")) {
    viewport.style.height = "min(" + (viewportMount.offsetWidth ?? viewportMount.scrollWidth) / 9 * 16 + "px, 100%)";
    viewport.style.width = "min(" + (viewportMount.offsetHeight ?? viewportMount.scrollHeight) / 16 * 9 + "px, 100%)";
    return;
  }
  
  viewport.style.width = "min(" + (viewportMount.offsetHeight ?? viewportMount.scrollHeight) / 9 * 16 + "px, 100%)";
  viewport.style.height = "min(" + (viewportMount.offsetWidth ?? viewportMount.scrollWidth) / 16 * 9 + "px, 100%)";
});

new ResizeObserver((entries) => {
  const vpm = entries[0];

  if (viewport.classList.contains("mobile")) {
    viewport.style.height = "min(" + vpm.contentRect.width / 9 * 16 + "px, 100%)";
    viewport.style.width = "min(" + vpm.contentRect.height / 16 * 9 + "px, 100%)";
    return;
  }
  
  viewport.style.width = "min(" + vpm.contentRect.height / 9 * 16 + "px, 100%)";
  viewport.style.height = "min(" + vpm.contentRect.width / 16 * 9 + "px, 100%)";
}).observe(viewportMount);



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
    let bool = false;
    let next = item;

    while (next != null) {
      if (next.classList.contains('drop') && next.classList.contains('settings-drop')) {
        bool = true;
      }
      next = next.parentElement;
    }

    return bool;
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
window.addEventListener('click', evt => {
  if (!HelperDropdown.isPartOfDrop(evt.target)) {
    settingItems.forEach(e => e.classList.remove('drop'))
    HelperDropdown.drop = false;
  }
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







//? controls resizer
$$(".controls .resize-divider").forEach(rd => {
  rd.addEventListener("pointerdown", evt => {
    rd.setPointerCapture(evt.pointerId);

    const pmove = pmoveEvt => {
      const parentHeight = rd.parentElement.clientHeight;
      console.log(parentHeight);
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
  window.page = root;
  document.widgetElement = root;
  document.querySelector("#viewport").appendChild(root.rootElement);
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











//* inspector
const inspectorRoot = $(".table > .inspector");
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
  HR(),
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
  DateInspector("2020-01-01", methods, "Date of upload"),
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

/**
 * @param {Widget} widget
 */
function inspect (widget) {
  inspectorRoot.textContent = "";
  inspectorRoot.append(...parseComponentContent(widget.inspectorHTML));
}














document.body.addEventListener("keydown", async evt => {
  if (evt.key === "s" && evt.ctrlKey) {
    evt.preventDefault();
    evt.stopImmediatePropagation();
    await save();
  }
});
async function save () {
  //TODO: add that if last snapshot of page is same as now cancel save
  const structure = window.page.save();
  
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