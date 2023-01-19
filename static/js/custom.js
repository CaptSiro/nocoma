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
 * @property {boolean} areCommentsAvailable
 * @property {boolean} isHomePage
 * @property {boolean} isPublic
 * @property {boolean} isTakenDown
 * @property {boolean} isTemplate
 * @property {string} src
 * @property {string} thumbnailSRC
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
        Img(AJAX.SERVER_HOME + "/public/images/theme-stock-pictures/__89754345.png", "post-image"), //TODO: change to post's bg image
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