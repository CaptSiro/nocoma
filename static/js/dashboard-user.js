//* create post
$("#create-post-button").addEventListener("click", () => showWindow("create-post"));
const createPost = $("#create-post");
const createPostTitle = $("#n-title");
const createPostCheckboxes = createPost.querySelectorAll("input[type=checkbox]");
const createPostErrorView = createPost.querySelector(".error");
createPost.querySelector("button.submit").addEventListener("click", () => {
  if (createPostTitle.value === "") {
    createPostErrorView.textContent = "You must define post's title.";
    createPostErrorView.classList.add("show");
    return;
  }
  
  createPostErrorView.textContent = "";
  createPostErrorView.classList.remove("show");
  
  const body = {
    title: createPostTitle.value
  };
  for (const checkbox of createPostCheckboxes) {
    body[checkbox.getAttribute("name")] = +checkbox.checked;
  }
  
  AJAX.post("/page/create", new JSONHandler(website => {
    if (website.error) {
      console.log(website);
      return;
    }
    
    window.location.replace(AJAX.SERVER_HOME + "/editor/" + website.src);
  }), {
    method: "POST",
    body: JSON.stringify(body)
  })
});







//*load posts
const postView = $(".post-view");
let callIndex = 0;

/**
 * @returns {Promise<HTMLElement|undefined>}
 */
function loadPosts () {
  return new Promise(resolve => {
    AJAX.get(`/page/${callIndex++}`, new JSONHandler(posts => {
      let element = undefined;
      
      for (const post of posts) {
        const redirectToEdit = () => {
          window.location.replace(AJAX.SERVER_HOME + "/editor/" + post.src);
        };
        element = html({
          className: "post",
          content: [{
            className: "absolute",
            content: [{
              name: "img",
              attributes: {
                src: AJAX.SERVER_HOME + "/public/images/theme-stock-pictures/__89754345.png",
                alt: "post-image"
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
                  name: "checkbox",
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
                  click: redirectToEdit
                }
              }]
            }]
          }, {
            className: "option-mount",
            content: [{
              className: "visible",
              content: [{
                name: "img",
                className: ["icon", "button-like"],
                attributes: {
                  src: AJAX.SERVER_HOME + "/public/images/options-white.svg",
                  alt: "opt"
                }
              }]
            }, {
              className: "menu-body",
              content: [{
                content: [{
                  name: "span",
                  className: "label",
                  textContent: "Edit",
                  listeners: { click: redirectToEdit }
                }]
              }, {
                listeners: {
                  click: evt => {
                    AJAX.delete("/page/delete/" + post.src, new JSONHandler(response => {
                      if (response.error !== undefined) {
                        //TODO: create my own alert
                        alert(response.error);
                        return;
                      }

                      evt.target.closest(".post").remove();
                    }));
                  }
                },
                content: [{
                  name: "span",
                  className: "label",
                  textContent: "Delete"
                }]
              }]
            }]
          }]
        });
        postView.appendChild(element);
      }
  
      console.log(posts, element);
      
      resolve(element);
    }));
  });
}
const postInfiniteScrolling = new IntersectionObserver(entries => {
  const lastPost = entries[0];
  
  if (!lastPost.isIntersecting) {
    return;
  }
  
  postInfiniteScrolling.unobserve(lastPost.target);
  loadPosts().then(last => {
    if (last !== undefined) {
      postInfiniteScrolling.observe(last);
    }
  });
}, {
  root: postView,
  rootMargin: "50px",
  threshold: 0
});
loadPosts().then(last => {
  if (last !== undefined) {
    postInfiniteScrolling.observe(last);
  }
});