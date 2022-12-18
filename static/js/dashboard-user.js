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







//* load posts
const postView = $(".post-view");
let postCallIndex = 0;

/**
 * @returns {Promise<HTMLElement|undefined>}
 */
function loadPosts () {
  return new Promise(resolve => {
    AJAX.get(`/page/${postCallIndex++}`, new JSONHandler(posts => {
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






//* upload files
$("#upload-files-button").addEventListener("click", () => showWindow("upload-files"));







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





//* load files
const fileView = $(".files-view");
let filesCallIndex = 0;

function fileSizeFormatter (size, inPowerOfTwo = false, decimal = 1) {
  const divider = inPowerOfTwo ? 1000 : 1024;
  
  if (Math.abs(size) < divider) {
    return size + " B";
  }
  
  const units = divider
    ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
    : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
  
  let unit = -1;
  const decimalPlaces = 10 ** decimal;
  
  do {
    size /= divider;
    unit++;
  } while (Math.round(Math.abs(size) * decimalPlaces) / decimalPlaces >= divider && unit < units.length - 1);
  
  return size.toFixed(decimal) + ' ' + units[unit];
}
const numberFormatter = Intl.NumberFormat("en", { notation: "compact" });

/**
 * @returns {Promise<HTMLElement|undefined>}
 */
function loadFiles () {
  return new Promise(resolve => {
    const order = localStorage.getItem("order") !== null ? localStorage.getItem("order") : "0";
    
    AJAX.get(`/file/${filesCallIndex++}/?order=${order}`, new JSONHandler(files => {
      let element = undefined;
  
      for (const file of files) {
        element = html({
          className: "file",
          content: [{
            className: "start",
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
              name: "span",
              className: "label",
              content: [{
                name: "span",
                className: "selectable",
                textContent: file.basename,
                listeners: {
                  dblclick: function () {
                    this.dataset.temporary = this.textContent;
                    this.setAttribute("contenteditable", "true");
                    this.focus();
                  },
                  blur: function () {
                    this.setAttribute("contenteditable", "false");
                    if (this.textContent === this.dataset.temporary) return;
                    
                    if (this.textContent === "") {
                      this.textContent = this.dataset.temporary;
                      rejected(this.closest(".file"));
                      return;
                    }
  
                    const value = this.textContent;
                    const that = this;
                    AJAX.patch("/file/" + file.src, new JSONHandler(response => {
                      if (response.error) {
                        //TODO: custom alert
                        that.textContent = that.dataset.temporary;
                        rejected(this.closest(".file"));
                        alert(response.error);
                        return;
                      }
  
                      validated(that.closest(".file"));
                    }), {
                      body: JSON.stringify({ value })
                    });
                  },
                  keydown: contentEditableLimiter(200)
                }
              }, {
                name: "span",
                textContent: file.extension
              }]
            }]
          }, {
            className: "end",
            content: [{
              name: "span",
              textContent: fileSizeFormatter(file.size)
            }, {
              name: "button",
              textContent: "X",
              listeners: {
                click: evt => {
                  AJAX.delete("/file/" + file.src, new JSONHandler(response => {
                    if (response.error) {
                      //TODO: create custom error alert
                      alert(response.error);
                      return;
                    }
                    
                    evt.target.closest(".file").remove();
                  }));
                }
              }
            }]
          }]
        });
        fileView.appendChild(element);
      }
      
      resolve(element);
    }));
  });
}
const fileInfiniteScrolling = new IntersectionObserver(entries => {
  const lastFile = entries[0];
  
  if (!lastFile.isIntersecting) {
    return;
  }
  
  fileInfiniteScrolling.unobserve(lastFile.target);
  loadFiles().then(last => {
    if (last !== undefined) {
      fileInfiniteScrolling.observe(last);
    }
  });
}, {
  root: fileView,
  rootMargin: "50px",
  threshold: 0
});
function resetFiles () {
  fileView.textContent = "";
  filesCallIndex = 0;
  fileInfiniteScrolling.disconnect();
  loadFiles().then(last => {
    if (last !== undefined) {
      fileInfiniteScrolling.observe(last);
    }
  });
}
resetFiles();



//TODO: remove
$("nav section button[title=Profile]").dispatchEvent(new CustomEvent("pointerdown"));


const selectedFilesMap = new Map();
const selectedFiles = $(".selected-files");
function displaySelectedFiles () {
  selectedFiles.textContent = "";
  selectedFilesMap.forEach((file) => {
    const fileElement = html({
      content: [{
        name: "button",
        textContent: "X",
        listeners: {
          click: () => {
            selectedFilesMap.delete(file.lastModified);
            fileElement.remove();
          }
        }
      }, {
        name: "span",
        textContent: file.name
      }]
    });
    
    selectedFiles.appendChild(fileElement);
  });
}

const dropArea = $("#upload-files .drop-area");
const fileUploadError = $("#upload-files .error");

dropArea.addEventListener("dragover", evt => {
  dropArea.classList.add("drag-over");
  evt.preventDefault();
});
dropArea.addEventListener("dragleave", () => dropArea.classList.remove("drag-over"));
dropArea.addEventListener("drop", evt => {
  evt.preventDefault();
  dropArea.classList.remove("drag-over");
  
  if (evt.dataTransfer.items) {
    [...evt.dataTransfer.items].forEach(item => {
      if (item.kind !== "file") return;
      
      const file = item.getAsFile();
      selectedFilesMap.set(file.lastModified, file);
    });
    
    displaySelectedFiles();
  }
});
$("#upload-files-input").addEventListener("change", evt => {
  [...evt.target.files].forEach(file => {
    selectedFilesMap.set(file.lastModified, file);
  });
  
  displaySelectedFiles();
});
$("#upload-files button[type=submit]").addEventListener("click", () => {
  if (selectedFilesMap.size === 0) {
    fileUploadError.textContent = "No files selected.";
    fileUploadError.classList.add("show");
    return;
  }
  
  fileUploadError.textContent = "";
  fileUploadError.classList.remove("show");
  
  const body = new FormData();
  selectedFilesMap.forEach(file => {
    body.append("uploaded[]", file);
  });

  AJAX.post("/file/collect", new JSONHandler(files => {
    if (files.error) {
      fileUploadError.textContent = files.error;
      fileUploadError.classList.add("show");
      return;
    }
    
    //TODO: update files-view
    clearWindows();
    resetFiles();
  
    selectedFilesMap.clear();
    selectedFiles.textContent = "";
  }), { body });
});
$("#upload-files .cancel-modal").addEventListener("click", () => {
  selectedFilesMap.clear();
  selectedFiles.textContent = "";
});

$$(".change-order").forEach(element => {
  element.addEventListener("click", () => {
    if (element.dataset.order === localStorage.getItem("order")) return;
    
    localStorage.setItem("order", element.dataset.order);
    resetFiles();
  });
});








//* user profile
const userName = $("#profile .username > h3");
function editUserName () {
  userName.dataset.temporary = userName.textContent;
  userName.setAttribute("contenteditable", "true");
  
  const range = document.createRange();
  const selection = window.getSelection();
  
  range.setStart(userName.childNodes[0], userName.textContent.length);
  range.collapse(true);
  selection.removeAllRanges();
  selection.addRange(range);
  
  userName.focus();
}
userName.addEventListener("dblclick", editUserName);
userName.addEventListener("keydown", contentEditableLimiter(32));
userName.addEventListener("blur", () => {
  userName.setAttribute("contenteditable", "false");
  if (userName.textContent === userName.dataset.temporary) return;
  
  if (userName.textContent === "") {
    userName.textContent = userName.dataset.temporary;
    rejected(userName.parentElement);
    return;
  }
  
  const value = userName.textContent;
  AJAX.patch("/profile/username/", new JSONHandler(response => {
    if (response.error) {
      userName.textContent = userName.dataset.temporary;
      rejected(userName.parentElement);
      //TODO: custom alert
      alert(response.error);
      return;
    }
    
    validated(userName.parentElement);
  }), {
    body: JSON.stringify({ value })
  });
});
$("#profile .username > button").addEventListener("click", editUserName);


$("#reset-password").addEventListener("click", () => {
  AJAX.post("/auth/password-recovery-email", new JSONHandler(json => {
    if (json.error !== undefined) {
      //TODO: custom alert
      alert(json.error);
      return;
    }
    
    alert("Email has been sent with link to password reset form.");
  }));
});