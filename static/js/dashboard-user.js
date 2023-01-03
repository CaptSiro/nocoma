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
    
    redirect(AJAX.SERVER_HOME + "/editor/" + website.src);
  }), {
    method: "POST",
    body: JSON.stringify(body)
  })
});







//* appeal for take down
let postToAppealFor = undefined;
/** @type {HTMLElement} */
let postElementToAppealFor = undefined;
const appealMessage = $("#appeal-message");
const appealError = $("#appeal .error-modal");
$("#appeal button[type=submit]").addEventListener("click", () => {
  if (appealMessage.value.length > 1024) {
    appealError.textContent = "Your message is too long. Maximum of 1024 characters is allowed.";
    appealError.classList.add("show");
    return;
  }
  
  appealError.classList.remove("show");
  appealError.textContent = "";
  
  AJAX.post("/page/appeal", new JSONHandler(response => {
    if (response.error) {
      alert(response.error);
      return;
    }
    
    if (response.rowCount !== 1) {
      alert("Internal server error");
      return;
    }
    
    appealMessage.value = "";
    clearWindows();
  }), {
    body: JSON.stringify({
      id: postToAppealFor.ID,
      message: appealMessage.value
    })
  });
});
$("#appeal button.cancel-modal").addEventListener("click", () => {
  appealMessage.value = "";
});



//* load posts
const postView = $(".post-view");


/**
 * @param {number} index
 * @returns {Promise<HTMLElement|undefined>}
 */
function loadPosts (index) {
  const type = localStorage.getItem("post-type") ?? "0";
  
  return new Promise(resolve => {
    AJAX.get(`/page/${index}/?type=${type}`, new JSONHandler(posts => {
      let element = undefined;
      
      for (const post of posts) {
        const optionsBody = html({
          className: "menu-body",
          content: [{
            listeners: { click: () => redirect(AJAX.SERVER_HOME + "/editor/" + post.src) },
            content: [{
              name: "span",
              className: "label",
              textContent: "Edit"
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
        });
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
                  click: () => redirect(AJAX.SERVER_HOME + "/editor/" + post.src)
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
            }, optionsBody]
          }],
          modify: postElement => {
            if (!post.isTakenDown) {
              return;
            }
            
            postElement.classList.add("taken-down");
            optionsBody.appendChild(html({
              content: [{
                name: "span",
                className: "label",
                textContent: "Appeal to remove take down",
                listeners: {
                  click: () => {
                    postToAppealFor = post;
                    postElementToAppealFor = postElement;
                    $("#post-title").textContent = post.title;
                    showWindow("appeal");
                  }
                }
              }]
            }));
          }
        });
        postView.appendChild(element);
      }
      
      resolve(element);
    }));
  });
}

const postScroller = new InfiniteScroller(postView, loadPosts);
changeUserPreferredSetting(".change-post-type", "type", "post-type", postScroller);





//* upload files
$("#upload-files-button").addEventListener("click", () => showWindow("upload-files"));





//* load files
const fileView = $(".files-view");

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

/**
 * @param {number} index
 * @returns {Promise<HTMLElement|undefined>}
 */
function loadFiles (index) {
  return new Promise(resolve => {
    const order = localStorage.getItem("order") !== null ? localStorage.getItem("order") : "0";
    
    AJAX.get(`/file/${index}/?order=${order}`, new JSONHandler(files => {
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
const filesInfiniteScroller = new InfiniteScroller(fileView, loadFiles);




// $("nav section button[title=Profile]").dispatchEvent(new CustomEvent("pointerdown"));









//* Galery
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
    filesInfiniteScroller.reset();
  
    selectedFilesMap.clear();
    selectedFiles.textContent = "";
  }), { body });
});
$("#upload-files .cancel-modal").addEventListener("click", () => {
  selectedFilesMap.clear();
  selectedFiles.textContent = "";
});

changeUserPreferredSetting(".change-order", "order", "order");