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






//? preloading page
/**
 * @type {{ pageWidget: Widget, inspector: Inspector }}
 */
window.page = {}






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
      ? widgetSelect.querySelectorAll(".widget.search-satisfactory")
      : widgetSelect.querySelectorAll(".widget")
    
    selectedWidget = widgetPool[!direction ? 0 : (widgetPool.length - 1)];
    selectedWidget.classList.add("selected");
    return;
  }
  
  selectedWidget.classList.remove("selected");
  let selectionPool = Array.from(widgetSelect.querySelectorAll(".widget"));
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


AJAX.get("/bundler/resource/*", new JSONHandler(json => {
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
    widgetSelect.appendChild(html({
      className: "widget-category",
      content: [{
        className: "label",
        content: {
          name: "h3",
          textContent: key
        }
      }, {
        className: "content",
        content: htmlCollection(grouped.get(key), resource => ({
          className: "widget",
          modify: widgetElement => {
            widgetElement.dataset.search = resource.properties.searchIndex;
            widgetElement.dataset.class = resource.properties.class;
          },
          content: [{
            name: "img",
            attributes: {
              src: resource.files.icon,
              alt: resource.files.icon.replace(filenameRegex, "")
            }
          }, {
            name: "span",
            textContent: resource.properties.label
          }],
          listeners: {
            click: function () {
              console.log(widgets.get(resource.properties.class).default(null));
            },
            mouseover: function () {
              widgetSelect.querySelectorAll(".widget").forEach(w => w.classList.remove("selected"));
              selectedWidget = this;
              this.classList.add("selected");
            }
          }
        }))
      }]
    }));
  }
}));
window.addEventListener("load", () => {
  const dataElement = $("#page-data");
  WRoot.build(JSON.parse(dataElement.textContent), null, true).then(root => {
    dataElement.remove();
    window.page.pageWidget = root;
    document.widgetElement = root;
    document.querySelector("#viewport").appendChild(root.rootElement);
  });
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
  
  console.log("visible")
  widgetSelect.style.visibility = "visible";
}