const userView = $(".user-view");


let postToTakeDown = undefined;
/**
 * @type {HTMLElement}
 */
let postElementToTakeDown = undefined;
const takeDownMessage = $("#take-down-message");
const takeDownError = $("#take-down-window .error-modal");
$("#take-down-window button[type=submit]").addEventListener("click", () => {
  console.dir(takeDownMessage)
  
  if (takeDownMessage.value === "") {
    takeDownError.textContent = "You must include a message.";
    takeDownError.classList.add("show");
    return;
  }
  
  if (takeDownMessage.value.length > 1024) {
    takeDownError.textContent = "Your message is too long. Maximum of 1024 characters is allowed.";
    takeDownError.classList.add("show");
    return;
  }
  
  takeDownError.classList.remove("show");
  takeDownError.textContent = "";
  
  AJAX.post("/page/take-down", new JSONHandler(response => {
    if (response.error) {
      alert(response.error);
      return;
    }
    
    if (response.rowCount !== 1) {
      alert("Internal server error");
      return;
    }
  
    postToTakeDown.isTakenDown = true;
    postElementToTakeDown.classList.add("taken-down");
    postElementToTakeDown.querySelector(".take-down-label").textContent = "Remove take down";
    
    clearWindows();
  }), {
    body: JSON.stringify({
      id: postToTakeDown.ID,
      message: takeDownMessage.value
    })
  });
});

/**
 * @returns {HTMLElement}
 */
function takeDownElement (post) {
  const label = html({
    name: "span",
    className: ["label", "take-down-label"],
    textContent: post.isTakenDown ? "Remove take down" : "Take down"
  });
  const element = html({
    content: label
  });
  
  element.onclick = () => {
    if (!post.isTakenDown) {
      postToTakeDown = post;
      postElementToTakeDown = element.closest(".post");
      $("#post-title").textContent = post.title;
      showWindow("take-down-window");
      takeDownMessage.focus();
      return;
    }
    
    AJAX.delete(`/page/take-down/`, new JSONHandler(response => {
      if (response.error) {
        alert(response.error);
        return;
      }
    
      if (response.rowCount !== 1) {
        return;
      }
  
      post.isTakenDown = false;
      element.closest(".post").classList.remove("taken-down");
      label.textContent = "Take down";
    }), {
      body: JSON.stringify({
        id: post.ID
      })
    });
  }
  
  return element;
}






/**
 * @param {*} user
 * @param {HTMLElement} container
 * @returns {(index: number)=>Promise<HTMLElement|undefined>}
 */
function loadUsersPosts (user, container) {
  return index => {
    return new Promise(resolve => {
      AJAX.get(`/users/${user.ID}/${index}`, new JSONHandler(posts => {
        let element = undefined;
        
        for (const post of posts) {
          const postsURL = AJAX.PROTOCOL + "://" + user.website + "." + AJAX.HOST_NAME + AJAX.DOMAIN_HOME + "/" + post.src;
          element = html({
            className: "post",
            modify: postHTMLElement => {
              if (!post.isTakenDown) {
                return;
              }
    
              postHTMLElement.classList.add("taken-down");
            },
            content: [{
              className: "absolute",
              content: [{
                name: "img",
                attributes: {
                  //TODO: posts image
                  src: AJAX.SERVER_HOME + "/public/images/theme-stock-pictures/__7612349654.png",
                  alt: "posts-bg"
                }
              }, {
                className: "darken"
              }]
            }, {
              className: "content",
              content: [{
                name: "label",
                className: "checkbox-container",
                content: [{
                  name: "input",
                  attributes: {
                    type: "checkbox",
                    id: "checkbox-0"
                  }
                }, {
                  name: "span"
                }]
              }, {
                content: [{
                  className: "date",
                  textContent: post.timeCreated
                }, {
                  name: "h3",
                  textContent: post.title,
                  listeners: {
                    click: () => window.open(postsURL, '_blank')
                  }
                }]
              }]
            }, {
              className: "option-mount",
              content: [{
                className: "visible",
                content: {
                  name: "img",
                  className: ["icon", "button-like"],
                  attributes: {
                    src: AJAX.SERVER_HOME + "/public/images/options-white.svg",
                    alt: "options"
                  }
                }
              }, {
                className: "menu-body",
                content: [{
                  content: [{
                    name: "span",
                    className: "label",
                    textContent: "View"
                  }],
                  listeners: {
                    click: () => window.open(postsURL, '_blank')
                  }
                }, takeDownElement(post)]
              }]
            }]
          });
          container.appendChild(element);
        }
        
        resolve(element);
      }))
    });
  };
}

/**
 * @param {number} index
 * @returns {Promise<HTMLElement|undefined>}
 */
function loadUsers (index) {
  const type = localStorage.getItem("user-type") ?? "0";
  
  return new Promise(resolve => {
    AJAX.get(`/users/${index}/?type=${type}`, new JSONHandler(users => {
      let element = undefined;
      
      for (const user of users) {
        const userPosts = html({
          className: "u-posts"
        });
  
        const userElement = html({
          className: "user",
          modify: userHTMLElement => {
            if (!user.isDisabled) {
              return;
            }
            
            userHTMLElement.classList.add("banned");
          },
          content: [{
            className: "u-head",
            content: [{
              className: "start",
              content: [{
                name: "img",
                className: "expand",
                attributes: {
                  src: AJAX.SERVER_HOME + "/public/images/expand.svg",
                  alt: "expand"
                },
                listeners: {
                  click: evt => {
                    userElement.classList.toggle("expanded");
                  }
                }
              }, {
                name: "img",
                attributes: {
                  src: AJAX.SERVER_HOME + "/profile/picture/" + user.ID,
                  alt: "pfp"
                }
              }, {
                className: "u-column",
                content: [{
                  name: "h4",
                  textContent: user.username
                }, {
                  name: "span",
                  textContent: user.website
                }]
              }]
            }, {
              className: "end",
              content: {
                className: "option-mount",
                content: [{
                  className: "visible",
                  content: {
                    name: "img",
                    className: ["icon", "button-like"],
                    attributes: {
                      src: AJAX.SERVER_HOME + "/public/images/options-white.svg",
                      alt: "options"
                    }
                  }
                }, {
                  className: "menu-body",
                  content: [{
                    content: [{
                      name: "span",
                      className: "label",
                      textContent: !user.isDisabled ? "Ban" : "Unban"
                    }],
                    modify: banOption => {
                      let banned = user.isDisabled;
                      const label = banOption.children[0];
                      
                      banOption.onclick = () => {
                        AJAX.patch(`/users/isDisabled/${user.ID}/${!banned}`, new JSONHandler(response => {
                          if (response.error) {
                            alert(response.error);
                            return;
                          }
      
                          if (response.rowCount !== 1) {
                            return;
                          }
      
                          banned = !banned;
                          userElement.classList.toggle("banned", banned);
                          label.textContent = banned ? "Unban" : "Ban";
                        }));
                      }
                    }
                  }]
                }]
              }
            }]
          }, userPosts]
        });
        new InfiniteScroller(userElement, loadUsersPosts(user, userPosts), () => {});
  
        element = userElement;
        userView.appendChild(element);
      }
      
      resolve(element);
    }));
  });
}
const userScroller = new InfiniteScroller(userView, loadUsers);

$$(".change-type").forEach(button => {
  button.addEventListener("click", () => {
    if (localStorage.getItem("user-type") === button.dataset.type) return;
    
    localStorage.setItem("user-type", button.dataset.type);
    userScroller.reset();
  });
});