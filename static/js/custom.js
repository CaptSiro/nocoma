function OptionVisible () {
  return (
    Div("visible", [
      SVG("icon-option", "icon button-like")
    ])
  );
}

/**
 * @param {string} label
 * @param {ComponentOptions} options
 */
function OptionBodyItem (label, options = undefined) {
  return (
    Div(__, [
      Span("label", label)
    ], options)
  );
}

/**
 * @typedef PostObject
 * @property {number} ID
 * @property {boolean} isHomePage
 * @property {boolean} isPublic
 * @property {boolean} isTakenDown
 * @property {boolean} isTemplate
 * @property {string} src
 * @property {string} thumbnailSRC
 * @property {string} thumbnail
 * @property {string} timeCreated
 * @property {string} title
 * @property {number} usersID
 *
 * @property {boolean} isFromAdminView
 * @property {string} redirectURL
 */
/**
 * @param {string} idGroup
 * @param {PostObject} post
 * @param {ComponentContent} optionBodyItems
 * @param {ComponentOptions} postOptions
 * @returns {HTMLElement}
 */
function PostComponent (idGroup, post, optionBodyItems, postOptions = undefined) {
  postOptions ||= {};
  postOptions.attributes ||= {};
  
  postOptions.attributes.id = idGroup + "_" + post.src;
  
  return Async(async () => {
    postOptions.attributes.style = post.thumbnail !== undefined
      ? `background-image: url(${AJAX.SERVER_HOME}/file/${post.src}/${post.thumbnail})`
      : `background-image: url(${await AJAX.get(
        "/auth/background",
        TextHandler(),
        {},
        AJAX.SERVER_HOME
      )})`;
    
    return (
      Div("post" + (post.isTakenDown ? " taken-down" : ""), [
        Div("content", [
          Div("post-info", [
            Div("date", "Created " + formatDate(new Date(post.timeCreated))),
            Heading(3, __, post.title, {
              listeners: {
                click: () => {
                  if (post.isFromAdminView) {
                    window.open(post.redirectURL, "_blank");
                    return;
                  }
                  
                  redirect(AJAX.SERVER_HOME + "/editor/" + post.src);
                }
              }
            })
          ]),
          Div("option-mount", [
            OptionVisible(),
            Div("menu-body", optionBodyItems)
          ])
        ]),
      ], postOptions)
    )
  }, Div("post"));
}



const zipFileMIMEs = ["application/gzip", "application/vnd.rar", "application/x-freearc", "application/x-bzip", "application/x-bzip2", "application/x-tar", "application/zip", "application/x-7z-compressed"];
const mimeRegex = /([a-z]+)\/.*/;
const supportedMIMEs = ["application", "audio", "font", "image", "model", "text", "video"];
/**
 * @param {string} mimeType
 * @param {Object.<string, string>} typeOverride
 */
function FileIcon (mimeType, typeOverride = {}) {
  const matches = mimeRegex.exec(mimeType);
  let iconURL = AJAX.SERVER_HOME + "/public/images/file-blank.svg";
  if (matches !== null) {
    if (supportedMIMEs.includes(matches[1])) {
      iconURL = AJAX.SERVER_HOME + `/public/images/file-${matches[1]}.svg`;
    }
    
    if (zipFileMIMEs.includes(mimeType)) {
      iconURL = AJAX.SERVER_HOME + `/public/images/file-archive.svg`;
    }
    
    if (typeOverride[matches[1]] !== undefined) {
      iconURL = typeOverride[matches[1]];
    }
  }
  
  return (
    Img(iconURL, "file icon", "file-icon")
  );
}


/**
 * @typedef Theme
 * @property {string} name
 * @property {string} src
 * @property {Map<string, string>} styles
 * @property {number} usersID
 */
/**
 * @param {*} base
 * @return {Theme}
 */
function parseTheme (base) {
  const variablesMap = new Map();
  for (const line of base.styles.split(/\r\n|\n/)) {
    const matches = /^\s*--([0-9a-zA-Z-_]+):\s*(.+);/.exec(line);
    if (matches === null) continue;
    variablesMap.set(matches[1], matches[2]);
  }
  
  base.styles = variablesMap;
  return base;
}
/**
 * @param {HTMLElement} themeSelect
 * @param {HTMLElement} themeContent
 * @param {HTMLElement} themeLabel
 * @return {()=>void}
 */
function makeThemeVisibleFactory (themeSelect, themeContent, themeLabel) {
  let isSecondInAsyncCallStack = false;
  return function themeVisibleMaker () {
    if (isSecondInAsyncCallStack === false) {
      isSecondInAsyncCallStack = true;
      return;
    }
    
    const themeSource = sessionStorage.getItem("themesSRC");
    
    Array.from(themeContent.children)
      .forEach(option => {
        if (!option.dataset.value.endsWith(themeSource)) return;
        themeSelect.value = option.dataset.value;
        themeLabel.innerText = option.innerText;
      });
    
    window.removeEventListener("themesLoaded", themeVisibleMaker);
    window.removeEventListener("themeSelect", themeVisibleMaker);
  }
}

/**
 * @param {()=>Promise<{error: string} | *>} ajaxRequester
 * @param {HTMLElement} themeSelect
 * @param {HTMLElement} themeContent
 * @param {HTMLElement} themeLabel
 * @return {(function(): Promise<void>)|*}
 */
function themeChangeListenerFactory(ajaxRequester, themeSelect, themeContent, themeLabel) {
  return async () => {
    const themeResponse = await ajaxRequester();
    
    if (themeResponse.error !== undefined) {
      console.log(themeResponse);
      return;
    }
    
    const themeLink = $(".themes-link");
    const newThemeLink = Component("link", "theme-link", __, {
      attributes: {
        id: "themes-link",
        rel: "stylesheet",
        href: themeSelect.dataset.value
      }
    });
    
    for (const option of themeContent.children) {
      if (option.dataset.value === themeSelect.dataset.value) {
        themeLabel.innerText = option.innerText;
        break;
      }
    }
    
    document.head.appendChild(newThemeLink);
    newThemeLink.addEventListener("load", async () => {
      await sleep(50);
      themeLink?.remove();
    });
  }
}
/**
 * @param {Theme} theme
 * @param {HTMLElement} themeSelect
 */
function ThemeColor (theme, themeSelect) {
  const value = AJAX.SERVER_HOME + "/theme/" + theme.src;
  
  return (
    Div("theme-colors", [
      Paragraph(__, theme.name)
    ], {
      attributes: {
        style: `
            --bg-0: ${theme.styles.get("container-0") ?? "#000"};
            --bg-1: ${theme.styles.get("container-opposite-3") ?? "#000"};
            --color-0: ${theme.styles.get("text-color-0") ?? "#000"};
            --color-1: ${theme.styles.get("text-color-opposite-3") ?? "#000"}
          `,
        "data-value": value
      },
      listeners: {
        click: evt => {
          evt.stopImmediatePropagation();
          themeSelect.classList.remove("expand");
        
          if (themeSelect.dataset.value === value) return;
  
          themeSelect.dataset.value = value;
          themeSelect.dispatchEvent(new Event("change"));
        }
      }
    })
  );
}