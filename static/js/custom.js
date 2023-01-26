function OptionVisible () {
  return (
    Div("visible", [
      Img(AJAX.SERVER_HOME + "/public/images/options-white.svg", "opt", "icon button-like")
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
  if (!postOptions) postOptions = {};
  if (!postOptions.attributes) postOptions.attributes = {};
  
  postOptions.attributes.id = idGroup + "_" + post.src;
  
  return (
    Div("post" + (post.isTakenDown ? " taken-down" : ""), [
      Div("absolute", [
        Img(
          post.thumbnail !== undefined
            ? `${AJAX.SERVER_HOME}/file/${post.src}/${post.thumbnail}`
            : AJAX.SERVER_HOME + "/public/images/login-register-bgs/9040950952.png",
          "post-image"
        ),
        Div("darken")
      ]),
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
  );
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