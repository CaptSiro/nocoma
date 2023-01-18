const __ = undefined;
/**
 * @type {"chrome" | "opera" | "firefox" | "safari" | "internet-explorer" | "edge" | "edge-chromium"}
 */
let browserType = "chrome";
(() => {
  if (!!document.documentMode) {
    browserType = !!window.StyleMedia
      ? "internet-explorer"
      : "edge";
    return;
  }
  
  if (typeof InstallTrigger !== 'undefined') {
    browserType = "firefox";
    return;
  }
  
  if ((!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0) {
    browserType = "opera";
    return;
  }
  
  if (navigator.userAgent.indexOf("Edg") !== -1) {
    browserType = "edge-chromium";
    return;
  }
  
  if (/constructor/i.test(window.HTMLElement)
    || (function (param) {
      return param.toString() === "[object SafariRemoteNotification]";
    })(!window['safari'] || (typeof safari !== 'undefined' && window['safari'].pushNotification))) {
    
    browserType = "safari";
  }
})();

function isChromiumBased () {
  return browserType === "chrome" || browserType === "edge-chromium"
};

/**
 * @param {String} css
 * @returns {HTMLElement}
 */
function $ (css) {
  return document.querySelector(css);
}
/**
 * @param {String} css
 * @returns {NodeListOf<Element>}
 */
function $$ (css) {
  return document.querySelectorAll(css);
}


/**
 * @param {string} selector
 * @return {Promise<HTMLElement>}
 */
function untilElement (selector) {
  return new Promise(resolve => {
    if (document.querySelector(selector)) {
      return resolve(document.querySelector(selector));
    }
    
    const observer = new MutationObserver(() => {
      if (document.querySelector(selector)) {
        resolve(document.querySelector(selector));
        observer.disconnect();
      }
    });
    
    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  });
}



const GUID_CHARSET = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_";
const guids = new Set();

/**
 * @param {boolean} forceFirstLetter
 * @param {number} length
 * @returns {string}
 */
function guid (forceFirstLetter = false, length = 8) {
  let id;
  const MAX_RETRIES = 10_000;
  let retry = 0;
  
  do {
    do {
      id = "";
      id += GUID_CHARSET[flatRNG(0, GUID_CHARSET.length)];
    } while (forceFirstLetter && !/^[a-zA-Z]$/.test(id));
    
    for (let i = 0; i < length - 1; i++) {
      id += GUID_CHARSET[flatRNG(0, GUID_CHARSET.length)];
    }
    
    if (++retry === MAX_RETRIES) break;
  } while (guids.has(id));
  
  if (retry === MAX_RETRIES) {
    return guid(length + 1);
  }
  
  guids.add(id);
  return id;
}

/**
 * @param {string} id
 */
function freeID (id) {
  guids.delete(id);
}





/**
 * @template T
 * @param {Object.<string, T> | undefined} object
 * @param {(key: string, value: T)=>void} setter
 */
function spreadObject (object, setter) {
  if (object === undefined) return;
  
  for (const key in object) {
    setter(key, object[key]);
  }
}

/**
 * @param {ComponentContent} content
 * @returns {Node[]}
 */
function parseComponentContent (content) {
  if (typeof content === "string") {
    return [document.createTextNode(content)];
  }
  
  if (content instanceof Node) {
    return [content];
  }
  
  return Array.from(content)
    .filter(node => (node instanceof Node || typeof node === "string"));
}

/**
 * @typedef {string | Node | ArrayLike.<HTMLElement | Node | string> | HTMLElement[] | HTMLCollection | undefined} ComponentContent
 */
/**
 * @typedef HTMLAttributes
 * @property {string=} src
 * @property {string=} alt
 * @property {string=} id
 * @property {string=} style
 * @property {string=} type
 * @property {string=} value
 */
/**
 * @typedef ComponentOptions
 * @property {HTMLAttributes | Object.<string, *>} attributes
 * @property {Object.<keyof HTMLElementEventMap, (evt?: Event)=>any> | undefined} listeners
 * @property {(element: HTMLElement)=>void=} modify
 */
/**
 * @param {keyof HTMLElementTagNameMap | string} tag
 * @param {string} className
 * @param {ComponentContent} content
 * @param {ComponentOptions} options
 * @returns {HTMLElement}
 */
function Component (tag, className = undefined, content = undefined, options = {}) {
  const component = document.createElement(tag);
  
  if (className) {
    component.className = className;
    // component.classList.add(...(
    //   className
    //     .split(" ")
    //     .filter(string => string !== "")
    // ));
  }
  
  if (content) {
    component.append(...parseComponentContent(content))
  }
  
  if (options.modify) {
    options.modify(component);
  }
  
  spreadObject(options.attributes, component.setAttribute.bind(component));
  spreadObject(options.listeners, component.addEventListener.bind(component));
  
  return component;
}


/**
 * @template C
 * @param {boolean} expression
 * @param {C} content
 * @returns {C}
 */
function OptionalComponent (expression, content) {
  return (
    expression
      ? content
      : undefined
  );
}


/**
 * @param {boolean} expression
 * @param {ArrayLike<HTMLElement | Node | string> | HTMLElement[] | HTMLCollection} content
 * @returns {[]}
 */
function OptionalComponents (expression, content) {
  return (
    expression
      ? content
      : []
  );
}


/**
 * Asynchronously replace placeholder element with element(s) returned by `asyncFunction`
 * @param {()=>Promise<(Node | HTMLElement)[] | Node | HTMLElement | HTMLCollection>} asyncFunction
 * @param {HTMLElement} placeholder
 */
function Async (asyncFunction, placeholder = undefined) {
  const id = guid(true);
  
  if (placeholder === undefined) {
    placeholder = Div();
  }
  
  placeholder.classList.add(id);
  
  untilElement("." + id)
    .then(asyncFunction)
    .then(component => {
      if (component instanceof Node) {
        component = [component];
      }
    
      for (const c of component) {
        placeholder.parentElement.insertBefore(c, placeholder);
      }
    
      placeholder.remove();
      freeID(id);
    });
  
  return placeholder;
}


/**
 * @param {string} className
 * @returns {HTMLElement}
 */
function HR (className = undefined) {
  return Component("hr", className);
}

/**
 * @param {string} className
 * @param {ComponentContent} content
 * @param {ComponentOptions} options
 * @returns {HTMLElement}
 */
function Div (className = undefined, content = undefined, options = {}) {
  return Component("div", className, content, options);
}

/**
 * @param {string} className
 * @param {ComponentContent} content
 * @param {ComponentOptions} options
 * @returns {HTMLElement}
 */
function Section (className = undefined, content = undefined, options = {}) {
  return Component("section", className, content, options);
}

/**
 * @param {string} className
 * @param {ComponentContent} content
 * @param {ComponentOptions} options
 * @returns {HTMLElement}
 */
function Span (className = undefined, content = undefined, options = {}) {
  return Component("span", className, content, options);
}

/**
 * @param {number} level
 * @param {string} className
 * @param {ComponentContent} content
 * @param {ComponentOptions} options
 * @returns {HTMLElement}
 */
function Heading (level, className = undefined, content = undefined, options = {}) {
  return Component("h" + clamp(1, 6, level), className, content, options);
}

/**
 * @param {string} className
 * @param {ComponentContent} content
 * @param {ComponentOptions} options
 * @returns {HTMLElement}
 */
function Paragraph (className = undefined, content = undefined, options = {}) {
  return Component("p", className, content, options);
}

/**
 * @param {string} href
 * @param {string} className
 * @param {ComponentContent} content
 * @param {ComponentOptions} options
 * @returns {HTMLElement}
 */
function Link (href, className = undefined, content = undefined, options = {}) {
  if (!options.attributes) {
    options.attributes = {};
  }
  
  options.attributes.href = href;
  
  return Component("a", className, content, options);
}

/**
 * @param {string} src
 * @param {string} alt
 * @param {string} className
 * @param {ComponentOptions} options
 * @returns {HTMLElement}
 */
function Img (src, alt, className = undefined, options = {}) {
  if (!options.attributes) {
    options.attributes = {};
  }
  
  options.attributes.src = src;
  options.attributes.alt = alt;
  
  return Component("img", className, __, options);
}



/**
 * @param {string} className
 * @param {ComponentContent} content
 * @param {(evt: Event)=>any | undefined} action
 * @param {ComponentOptions} options
 * @returns {HTMLElement}
 */
function Button (className = undefined, content = undefined, action = undefined, options = {}) {
  if (action) {
    if (!options.listeners) {
      options.listeners = {};
    }
    
    options.listeners.click = action;
    options.listeners.submit = action;
  }
  
  return Component("button", className, content, options);
}

/**
 * @param {string} className
 * @param {ComponentContent} content
 * @param {ComponentOptions} options
 * @returns {HTMLElement}
 */
function Label (className = undefined, content = undefined, options = {}) {
  return Component("label", className, content, options);
}

/**
 * @typedef {"button" | "checkbox" | "color" | "date" | "datetime-local" | "email" | "file" | "hidden" | "image" | "month" | "number" | "password" | "radio" | "range" | "reset" | "search" | "submit" | "tel" | "text" | "time" | "url" | "week"} InputTypes
 */
/**
 * @param {InputTypes} type
 * @param {string} className
 * @param {ComponentOptions} options
 * @returns {HTMLElement}
 */
function Input (type, className = undefined, options = {}) {
  if (!options.attributes) {
    options.attributes = {};
  }
  
  options.attributes.type = type;
  
  return Component("input", className, __, options);
}

/**
 * @param {string} label
 * @param {string} className
 * @param {string | any} id
 * @param {ComponentOptions} checkboxOptions
 */
function Checkbox (label = "", className = undefined, id = undefined, checkboxOptions = undefined) {
  if (!id) {
    id = guid(true);
  }
  
  if (!checkboxOptions) checkboxOptions = {};
  if (!checkboxOptions.attributes) checkboxOptions.attributes = {};
  
  checkboxOptions.attributes.id = id;
  
  return (
    Label("checkbox-container" + (className ? (" " + className) : ""), [
      Input("checkbox", __, checkboxOptions),
      Span(__, label)
    ], {
      attributes: {
        for: id
      }
    })
  );
}

/**
 * @param {string} label
 * @param {string} value
 * @param {string} name
 * @param {string} className
 * @param {ComponentOptions} radioOptions
 */
function Radio (label, value, name, className = undefined, radioOptions = undefined) {
  let id = guid(true);
  
  if (!radioOptions) radioOptions = {};
  if (!radioOptions.attributes) radioOptions.attributes = {};
  
  radioOptions.attributes.name = name;
  radioOptions.attributes.value = value;
  
  if (radioOptions.attributes.id) {
    id = radioOptions.attributes.id;
  } else {
    radioOptions.attributes.id = id;
  }
  
  return (
    Label("radio-container" + (className ? (" " + className) : ""), [
      Input("radio", __, radioOptions),
      Span(__, label)
    ], {
      attributes: {
        for: id
      }
    })
  );
}

/**
 * @param {"error", "note"} type
 * @param {ComponentContent} content
 * @returns {HTMLElement}
 */
function Blockquote (type, content = undefined) {
  return (
    Div("blockquote " + type, content)
  );
}



/**
 * @param {string} content
 * @param {boolean} isCollection
 * @returns {NodeListOf<ChildNode> | ChildNode}
 */
function HTML (content, isCollection = false) {
  const template = document.createElement("template");
  template.innerHTML = content.trim();
  return isCollection
    ? template.content.childNodes
    : template.content.firstChild;
}





const formatter = new Intl.RelativeTimeFormat(undefined, {
  numeric: 'auto'
});

const DIVISIONS = [{
  amount: 60,
  name: 'seconds'
}, {
  amount: 60,
  name: 'minutes'
}, {
  amount: 24,
  name: 'hours'
}, {
  amount: 7,
  name: 'days'
}, {
  amount: 4.34524,
  name: 'weeks'
}, {
  amount: 12,
  name: 'months'
}, {
  amount: Number.POSITIVE_INFINITY,
  name: 'years'
}];

function formatDate (date) {
  let duration = (date - new Date()) / 1000;
  
  for (let i = 0; i <= DIVISIONS.length; i++) {
    const division = DIVISIONS[i];
    if (Math.abs(duration) < division.amount) {
      return formatter.format(Math.round(duration), division.name);
    }
    duration /= division.amount;
  }
}



/**
 * @template T, R
 * @param {T[]} array1
 * @param {R[]}  array2
 * @param {(a: T, b: R)=>boolean} compareFunction
 * @returns {boolean}
 */
function arrayEqual (array1, array2, compareFunction = ((a, b) => a === b)) {
  if (array1.length !== array2.length) return false;
  
  for (let i = 0; i < array1.length; i++) {
    if (!compareFunction(array1[i], array2[i])) return false;
  }
  
  return true;
}




/**
 * Clamps number between given bounds
 * @param {Number} min
 * @param {Number} max
 * @param {Number} number
 * @returns {Number}
 */
function clamp (min, max, number) {
  if (number < min) return min;
  return number > max ? max : number;
}



/**
 * Adds delay to running code synchronously
 * @param {Number} ms
 * @returns
 */
function sleep (ms) {
  return new Promise(
    (resolve) => setTimeout(
      () => resolve(),
      ms
    )
  );
}



/**
 * @param {number} from
 * @param {number} to
 * @returns {number}
 */
function rng (from, to) {
  return Math.random() * (Math.max(from, to) - Math.min(from, to)) + from;
}



/**
 * @param {number} from
 * @param {number} to
 * @returns {number}
 */
function flatRNG (from, to) {
  return Math.floor(rng(from, to));
}



/**
 * @param {Object.<string, string | Blob>} object
 * @returns {FormData}
 */
function toFormData (object) {
  const formData = new FormData();
  for (const key in object) {
    formData.append(key, object[key]);
  }
  return formData;
}



/**
 * @param {HTMLElement} element
 * @param {string[]} classes
 * @returns {number} timeoutID
 */
function pulse (element, classes) {
  element.classList.add("transition-background", ...classes);
  
  return setTimeout(() => {
    element.classList.remove(...classes);
    
    setTimeout(() => {
      element.classList.remove("transition-background");
    }, 500);
  }, 1000);
}

/**
 * @param {HTMLElement} element
 * @param {boolean} dark
 * @returns {number} timeoutID
 */
function validated (element, dark = false) {
  return pulse(element, ["validated", ...(dark ? ["darken"] : [])]);
}

/**
 * @param {HTMLElement} element
 * @param {boolean} dark
 * @returns {number} timeoutID
 */
function rejected (element, dark = false) {
  return pulse(element, ["rejected", ...(dark ? ["darken"] : [])]);
}

/**
 * @param {string} destination
 * @param {boolean} savePositionToHistory
 */
function redirect (destination, savePositionToHistory = true) {
  if (savePositionToHistory) {
    history.pushState({}, '', new URL(window.location));
  }
  
  window.location.replace(destination);
}

/**
 * @param {number} maxSize
 * @returns {(function(evt: Event): void)}
 */
function contentEditableLimiter (maxSize = 16) {
  return function (evt) {
    const isRemovalKey = evt.key === "Backspace" || evt.key === "Delete";
    const isNavigationKey = evt.key === "ArrowLeft" || evt.key === "ArrowUp" || evt.key === "ArrowDown" || evt.key === "ArrowRight";
    const exceededLength = this.textContent.length > maxSize;
    
    if (exceededLength && !isRemovalKey && !isNavigationKey) {
      evt.preventDefault();
    }
    
    if (evt.key === "Enter") {
      evt.preventDefault()
      this.blur();
    }
  }
}





/**
 * @callback Handler
 * @param {Response} response
 * @returns {*}
 */

/**
 * @returns {Handler}
 */
function ResponseHandler () {
  return response => response
}

/**
 * @param {(json: *)=>void} callback
 * @returns {Handler}
 */
function JSONHandlerSync (callback) {
  return (response) => {
    return new Promise((resolve, reject) => {
      response.json()
        .then(json => {
          resolve(callback(json));
        })
        .catch(reason => reject(reason))
    });
  }
}
/**
 * @returns {Handler}
 */
function JSONHandler () {
  return JSONHandlerSync(json => json);
}

/**
 * @template R
 * @param {(text: string)=>R} callback
 * @returns {Handler}
 */
function TextHandlerSync (callback) {
  return (response) => {
    return new Promise((resolve, reject) => {
      response.text()
        .then(text => {
          resolve(callback(text));
        })
        .catch(reason => reject(reason))
    });
  }
}

/**
 * @returns {Handler}
 */
function TextHandler () {
  return TextHandlerSync(text => text);
}

class AJAX {
  static DOMAIN_HOME = "";
  static SERVER_HOME = "";
  static HOST_NAME = "";
  static PROTOCOL = "";
  
  static #logResponseError (response) {
    return (text) => {
      console.error(response.statusText);
      console.log(text);
    }
  }
  
  /**
   * @param {string} method
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   * @returns {Promise}
   */
  static #request (method, url, handler = response => response, options = {}) {
    return new Promise((resolve, reject) => {
      options.method = method;
      
      fetch(url, options).then((response) => {
        const responseText = response.clone();
        
        if (!response.ok) {
          responseText.text().then(this.#logResponseError(response));
          reject(response.status + " " + response.statusText);
          return;
        }
  
        const value = handler(response);
        if (!value instanceof Promise) {
          resolve(value);
        }
        
        value
          .then(resolve)
          .catch(() => {
            responseText.text().then(this.#logResponseError(response));
            reject(responseText);
          })
      });
    });
  }
  
  /**
   * @param {string} method
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   * @returns {Promise}
   */
  static fetch (method, url, handler = response => response, options = {}) {
    return this.#request(method, url, handler, options);
  }
  
  /**
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   * @param {string} home
   * @returns {Promise}
   */
  static get (url, handler = response => response, options = {}, home = undefined) {
    return this.#request("GET", ((home ?? AJAX.DOMAIN_HOME) + url), handler, options);
  }
  
  /**
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   * @param {string} home
   * @returns {Promise}
   */
  static head (url, handler = response => response, options = {}, home = undefined) {
    return this.#request("HEAD", ((home ?? AJAX.DOMAIN_HOME) + url), handler, options);
  }
  
  /**
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   * @param {string} home
   * @returns {Promise}
   */
  static post (url, handler = response => response, options = {}, home = undefined) {
    return this.#request("POST", ((home ?? AJAX.DOMAIN_HOME) + url), handler, options);
  }
  
  /**
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   * @param {string} home
   * @returns {Promise}
   */
  static put (url, handler = response => response, options = {}, home = undefined) {
    return this.#request("PUT", ((home ?? AJAX.DOMAIN_HOME) + url), handler, options);
  }
  
  /**
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   * @param {string} home
   * @returns {Promise}
   */
  static delete (url, handler = response => response, options = {}, home = undefined) {
    return this.#request("DELETE", ((home ?? AJAX.DOMAIN_HOME) + url), handler, options);
  }
  
  /**
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   * @param {string} home
   * @returns {Promise}
   */
  static connect (url, handler = response => response, options = {}, home = undefined) {
    return this.#request("CONNECT", ((home ?? AJAX.DOMAIN_HOME) + url), handler, options);
  }
  
  /**
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   * @param {string} home
   * @returns {Promise}
   */
  static trace (url, handler = response => response, options = {}, home = undefined) {
    return this.#request("TRACE", ((home ?? AJAX.DOMAIN_HOME) + url), handler, options);
  }
  
  /**
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   * @param {string} home
   * @returns {Promise}
   */
  static patch (url, handler = response => response, options = {}, home = undefined) {
    return this.#request("PATCH", ((home ?? AJAX.DOMAIN_HOME) + url), handler, options);
  }
}