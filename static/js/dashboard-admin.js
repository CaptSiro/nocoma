const userView = $(".user-view");


let postToTakeDown = undefined;
/**
 * @type {HTMLElement}
 */
let postElementToTakeDown = undefined;
const takeDownMessage = $("#take-down-message");
const takeDownError = $("#take-down-window .error-modal");
$("#take-down-window button[type=submit]").addEventListener("click", () => {
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
  
  AJAX.post("/page/take-down", JSONHandlerSync(response => {
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
    takeDownMessage.value = "";
    
    clearWindows();
  }), {
    body: JSON.stringify({
      id: postToTakeDown.ID,
      message: takeDownMessage.value
    })
  });
});
$("#take-down-window button.cancel-modal").addEventListener("click", () => {
  takeDownMessage.value = "";
});


/**
 * @returns {HTMLElement}
 */
function takeDownElement (post) {
  const label = Span("label take-down-label", post.isTakenDown ? "Remove take down" : "Take down");
  
  const element = Div(__, label, {
    listeners: {
      click: async () => {
        if (!post.isTakenDown) {
          postToTakeDown = post;
          postElementToTakeDown = element.closest(".post");
          $("#post-title").textContent = post.title;
          showWindow("take-down-window");
          takeDownMessage.focus();
          return;
        }
  
        await AJAX.delete(`/page/take-down/`, JSONHandlerSync(response => {
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
    }
  });
  
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
      AJAX.get(`/users/${user.ID}/${index}`, JSONHandlerSync(posts => {
        let element = undefined;
        
        for (const post of posts) {
          const postsURL = createPostLink(user.website, post.src);
          
          post.redirectURL = postsURL;
          post.isFromAdminView = true;
          
          element = (
            PostComponent("users", post, [
              OptionBodyItem("View", {
                listeners: {
                  click: () => window.open(postsURL, '_blank')
                }
              }),
              takeDownElement(post)
            ])
          );
          
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
    AJAX.get(`/users/${index}/?type=${type}`, JSONHandlerSync(users => {
      let element = undefined;
      
      for (const user of users) {
        const userPosts = Div("u-posts");
  
        const userElement = Div("user" + (user.isDisabled ? " banned" : ""), [
          Div("u-head", [
            Div(__, [
              Div("start", [
                SVG("icon-arrow", "expand", __, {
                  listeners: {
                    click: () => userElement.classList.toggle("expanded")
                  }
                }),
                Img(AJAX.SERVER_HOME + "/profile/picture/" + user.ID, "pfp"),
                Div("u-column", [
                  Heading(4, __, user.username),
                  Span(__, user.website)
                ]),
              ]),
              Div("end", [
                Div("option-mount", [
                  OptionVisible(),
                  Div("menu-body", [
                    OptionBodyItem(
                      !user.isDisabled
                        ? "Ban"
                        : "Unban",
                      {
                        modify: banOption => {
                          let banned = user.isDisabled;
                          const label = banOption.children[0];
        
                          banOption.onclick = () => {
                            AJAX.patch(`/users/isDisabled/${user.ID}/${!banned}`, JSONHandlerSync(response => {
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
                      }
                    )
                  ])
                ])
              ]),
            ]),
          ]),
          userPosts
        ]);
        
        new InfiniteScroller(userElement, loadUsersPosts(user, userPosts), () => {});
  
        element = userElement;
        userView.appendChild(element);
      }
      
      resolve(element);
    }));
  });
}

const userScroller = new InfiniteScroller(userView, loadUsers);
changeUserPreferredSetting(".change-type", "type", "user-type", userScroller);








const appealView = $(".appeals-view");

/**
 * @param {number} appealID
 * @param {HTMLElement} appealElement
 * @returns {(function(): void)|*}
 */
function setAsReadFactory (appealID, appealElement) {
  return function __listener () {
    AJAX.patch(`/page/appeal/${appealID}/`, JSONHandlerSync(response => {
      if (response.error) {
        alert(response.error);
        return;
      }
    
      if (response.rowCount !== 1) {
        alert("Internal server error");
        return;
      }
  
      appealElement.classList.remove("not-read");
      appealElement.removeEventListener("click", __listener);
    }))
  }
}
const appealScroll = new InfiniteScroller(appealView, (index) => {
  const type = localStorage.getItem("appeal-type") ?? "0";

  return new Promise(resolve => AJAX.get(`/page/appeal/${index}/?type=${type}`, JSONHandlerSync(appeals => {
    let element = undefined;

    for (const appeal of appeals) {
      const appealElement = (
        Div("appeal", [
          Div("a-head", [
            Div("u-head", [
              Div("start", [
                Img(AJAX.SERVER_HOME + "/profile/picture/" + appeal.usersID, "pfp for " + appeal.username),
                Div("u-column", [
                  Heading(4, __, appeal.username),
                  Span(__, appeal.website)
                ])
              ])
            ])
          ]),
          Div("a-body", [
            Div("related-post", [
              Heading(3, __, appeal.title)
            ]),
            Div("a-msg" + (appeal.message == null || appeal.message === "" ? " null" : ""), String(appeal.message)),
            Div("a-controls", [
              Button(__, "View post", () => window.open(createPostLink(appeal.website, appeal.src), "_blank")),
              Button(__, "Accept", evt => {
                if (!confirm("Are sure you want to ACCEPT appeal for " + appeal.title + " by " + appeal.username + "/" + appeal.website)) {
                  return;
                }
                
                evt.stopPropagation();
          
                AJAX.delete(`/page/appeal/${appeal.ID}/accept`, JSONHandlerSync(response => {
                  if (response.error) {
                    alert(response.error);
                    return;
                  }
            
                  if (response.rowCount !== 1) {
                    alert("Internal server error");
                    return;
                  }
            
                  evt.target.closest(".appeal").remove();
                }))
              }),
              Button(__, "Decline", (evt) => {
                if (!confirm("Are sure you want to DECLINE appeal for " + appeal.title + " by " + appeal.username + "/" + appeal.website)) {
                  return;
                }
                
                evt.stopPropagation();
          
                AJAX.delete(`/page/appeal/${appeal.ID}/decline`, JSONHandlerSync(response => {
                  if (response.error) {
                    alert(response.error);
                    return;
                  }
            
                  if (response.rowCount !== 1) {
                    alert("Internal server error");
                    return;
                  }
            
                  evt.target.closest(".appeal").remove();
                }))
              })
            ])
          ])
        ])
      );
  
      if (appeal.hasBeenRead === false) {
        appealElement.addEventListener("click", setAsReadFactory(appeal.ID, appealElement));
        appealElement.classList.add("not-read");
      }

      element = appealElement;
      appealView.appendChild(element);
    }

    resolve(element);
  })));
});
changeUserPreferredSetting(".change-appeal-type", "type", "appeal-type", appealScroll);