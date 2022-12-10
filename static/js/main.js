/**
 *
 * @param {String} css
 * @returns {HTMLElement}
 */
const $ = (css) => document.querySelector(css);
/**
 *
 * @param {String} css
 * @returns {NodeListOf<Element>}
 */
const $$ = (css) => document.querySelectorAll(css);




/**
 * @typedef HTMLAttributes
 * @prop {String=} src
 * @prop {String=} alt
 * @prop {String=} id
 * @prop {String=} style
 * @prop {String=} type
 * @prop {String=} value
 */
/**
 * @typedef HTMLEventListeners
 * @prop {(event: Event)=>void=} load
 * @prop {(event: Event)=>void=} resize
 * @prop {(event: Event)=>void=} input
 * @prop {(event: Event)=>void=} submit
 * @prop {(event: KeyboardEvent)=>void=} keydown
 * @prop {(event: KeyboardEvent)=>void=} keyup
 * @prop {(event: KeyboardEvent)=>void=} keypress
 * @prop {(event: MouseEvent)=>void=} click
 * @prop {(event: MouseEvent)=>void=} contextmenu
 * @prop {(event: MouseEvent)=>void=} dblclick
 * @prop {(event: MouseEvent)=>void=} mousedown
 * @prop {(event: MouseEvent)=>void=} mousemove
 * @prop {(event: MouseEvent)=>void=} mouseleave
 * @prop {(event: MouseEvent)=>void=} mouseover
 * @prop {(event: MouseEvent)=>void=} mouseup
 * @prop {(event: MouseEvent)=>void=} mousewheel
 * @prop {(event: MouseEvent)=>void=} wheel
 * @prop {(event: DragEvent)=>void=} drag
 * @prop {(event: DragEvent)=>void=} scroll
 * @prop {(event: ClipboardEvent)=>void=} copy
 * @prop {(event: ClipboardEvent)=>void=} cut
 * @prop {(event: ClipboardEvent)=>void=} paste
 * @prop {(event: FocusEvent)=>void=} blur
 * @prop {(event: FocusEvent)=>void=} focus
 * @prop {(event: Event)=>void=} ended
 * @prop {(event: Event)=>void=} error
 * @prop {(event: Event)=>void=} loadeddata
 * @prop {(event: Event)=>void=} pause
 * @prop {(event: Event)=>void=} play
 */
/**
 * @typedef HTMLDescription
 * @prop {keyof HTMLElementTagNameMap=} name Setting name to undefined will resolve to default tag name: "div"
 * @prop {String|String[]=} className
 * @prop {HTMLAttributes=} attributes
 * @prop {HTMLEventListeners=} listeners
 * @prop {String|String[]|HTMLDescription|HTMLDescription[]|HTMLElement|HTMLElement[]=} content
 * @prop {String=} textContent
 * @prop {(element: HTMLElement)=>void=} modify
 */
/**
 * From given description creates an HTMLElement and returns it
 * @param {HTMLDescription} description
 * @returns {HTMLElement}
 */
const html = function (description) {
  if (description === undefined) {
    throw new Error("No parameters were passed into html function.");
  }

  if (description.name === undefined || typeof description.name !== "string") {
    description.name = "div";
  }

  const element = document.createElement(description.name);

  if (description.className) {
    if (description.className instanceof Array) {
      for (let i = 0; i < description.className.length; i++) {
        element.classList.add(description.className[i]);
      }
    } else {
      element.classList.add(description.className);
    }
  }

  if (description.attributes) {
    if (description.attributes instanceof Object) {
      for (const attr in description.attributes) {
        element.setAttribute(attr, description.attributes[attr]);
      }
    }
  }

  if (description.listeners) {
    if (description.listeners instanceof Object) {
      for (const event in description.listeners) {
        element.addEventListener(event, description.listeners[event]);
      }
    }
  }

  if (description.textContent) {
    element.textContent = description.textContent;
  }

  if (description.content && description.textContent == undefined) {
    if (typeof description.content === "string") {
      element.appendChild(document.createTextNode(description.content));
    } else if (description.content instanceof HTMLElement) {
      element.appendChild(description.content);
    } else if (
      description.content instanceof Object &&
      !(description.content instanceof Array)
    ) {
      element.appendChild(html(description.content));
    } else if (description.content instanceof Array) {
      for (let i = 0; i < description.content.length; i++) {
        if (typeof description.content[i] === "string") {
          element.appendChild(document.createTextNode(description.content[i]));
        } else if (description.content[i] instanceof HTMLElement) {
          element.appendChild(description.content[i]);
        } else {
          element.appendChild(html(description.content[i]));
        }
      }
    }
  }

  if (description.modify) {
    description.modify(element);
  }

  return element;
};

/**
 * For each item in `rawArray` calls `builder` callback and from returned description creates HTMLElement
 * @template {any} T
 * @param {Array<T>} rawArray
 * @param {(rawItem: T) => HTMLDescription} builder
 * @returns {HTMLElement[]}
 */
const htmlCollection = function (rawArray, builder) {
  return rawArray.map((rawItem) => html(builder(rawItem)));
};



/**
 * Clamps number between given bounds
 * @param {Number} min
 * @param {Number} max
 * @param {Number} number
 * @returns {Number}
 */
const clamp = (min, max, number) => {
  if (number < min) return min;
  return number > max ? max : number;
};

/**
 * Adds delay to running code synchronously
 * @param {Number} ms
 * @returns
 */
const sleep = (ms) => new Promise(
  (resolve, reject) => setTimeout(
    () => resolve(),
    ms
  )
);

/**
 * @param {number} from
 * @param {number} to
 * @returns {number}
 */
const RNG = (from, to) =>
  Math.random() * (Math.max(from, to) - Math.min(from, to)) + from;
/**
 * @param {number} from
 * @param {number} to
 * @returns {number}
 */
const flatRNG = (from, to) => Math.floor(RNG(from, to));





/**
 * @param {*} obj 
 * @returns {FormData}
 */
const toFormData = obj => {
  const fd = new FormData();
  for (const k in obj) {
    fd.append(k, obj[k]);
  }
  return fd;
}







class Handler {
  #fun;
  
  /**
   * @param {(Response: response)=>void} fun
   */
  constructor(fun) {
    this.#fun = fun;
  }
  
  /**
   * @param {Response} response
   */
  call (response) {
    this.#fun(response);
  }
}

class JSONHandler extends Handler {
  constructor(fun) {
    super((response) => {
      response.json().then(json => {
        fun(json);
      });
    });
  }
}
class TextHandler extends Handler {
  constructor(fun) {
    super((response) => {
      response.text().then(text => {
        fun(text);
      });
    });
  }
}
const phpExceptionHandlerFactory = (element) => (
  text => {
    element.innerHTML = text;
  }
)
class NonHTMLTextHandler extends Handler {
  #phpExceptionHandler;
  
  constructor(fun, phpExceptionHandler) {
    super((response) => {
      response.text().then(text => {
        if (text[0] === "<") {
          phpExceptionHandler(text);
          return;
        }
  
        fun(text);
      });
    });
  }
}

class AJAX {
  static HOME = "/nocoma";
  
  static #logResponseError (response) {
    return (text) => {
      console.error(response.statusText);
      console.log(text);
    }
  }
  
  /**
   *
   * @param {string} method
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   */
  static #request (method, url, handler, options = {}) {
    options.method = method;
    fetch(url, options).then((response) => {
      const responseText = response.clone();
    
      if (!response.ok) {
        responseText.text().then(this.#logResponseError(response));
        return null;
      }
  
      handler?.call(response);
    });
  }
  
  /**
   * @param {string} method
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   */
  static fetch (method, url, handler, options = {}) {
    this.#request(method, url, handler, options);
  }
  
  /**
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   */
  static get (url, handler, options = {}) {
    this.#request("GET", (AJAX.HOME + url), handler, options);
  }
  
  /**
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   */
  static head (url, handler, options = {}) {
    this.#request("HEAD", (AJAX.HOME + url), handler, options);
  }
  
  /**
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   */
  static post (url, handler, options = {}) {
    this.#request("POST", (AJAX.HOME + url), handler, options);
  }
  
  /**
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   */
  static put (url, handler, options = {}) {
    this.#request("PUT", (AJAX.HOME + url), handler, options);
  }
  
  /**
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   */
  static delete (url, handler, options = {}) {
    this.#request("DELETE", (AJAX.HOME + url), handler, options);
  }
  
  /**
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   */
  static connect (url, handler, options = {}) {
    this.#request("CONNECT", (AJAX.HOME + url), handler, options);
  }
  
  /**
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   */
  static trace (url, handler, options = {}) {
    this.#request("TRACE", (AJAX.HOME + url), handler, options);
  }
  
  /**
   * @param {string} url
   * @param {Handler} handler
   * @param {RequestInit=} options
   */
  static patch (url, handler, options = {}) {
    this.#request("PATCH", (AJAX.HOME + url), handler, options);
  }
}