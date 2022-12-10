const vp = $("#viewport");
const vpmount = $(".viewport-mount");
$(".toggle-viewport").addEventListener("pointerdown", evt => {
  vp.classList.toggle("mobile");

  if (vp.classList.contains("mobile")) {
    vp.style.height = "min(" + (vpmount.offsetWidth ?? vpmount.scrollWidth) / 9 * 16 + "px, 100%)";
    vp.style.width = "min(" + (vpmount.offsetHeight ?? vpmount.scrollHeight) / 16 * 9 + "px, 100%)";
    return;
  }
  
  vp.style.width = "min(" + (vpmount.offsetHeight ?? vpmount.scrollHeight) / 9 * 16 + "px, 100%)";
  vp.style.height = "min(" + (vpmount.offsetWidth ?? vpmount.scrollWidth) / 16 * 9 + "px, 100%)";
});

new ResizeObserver((entries) => {
  const vpm = entries[0];

  if (vp.classList.contains("mobile")) {
    vp.style.height = "min(" + vpm.contentRect.width / 9 * 16 + "px, 100%)";
    vp.style.width = "min(" + vpm.contentRect.height / 16 * 9 + "px, 100%)";
    return;
  }
  
  vp.style.width = "min(" + vpm.contentRect.height / 9 * 16 + "px, 100%)";
  vp.style.height = "min(" + vpm.contentRect.width / 16 * 9 + "px, 100%)";
}).observe(vpmount);



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
    if (HelperDropdown.drop == true && !e.classList.contains("drop")) {
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
AJAX.get("/bundler/resource/*", new JSONHandler(json => {
  /** @type {Map<string, { properties: { category: string, label: string, class: string }, files: { icon: string } }[]>} */
  const grouped = Array.from(json)
    .filter(resource => resource.properties.category !== "Hidden")
    .reduce((map, resource) => {
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
            click: function (evt) {
              console.log(window[resource.properties.class].default());
            }
          }
        }))
      }]
    }));
  }
}));