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
  
  AJAX.post("/page/create", JSONHandlerSync(website => {
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
  
  AJAX.post("/page/appeal", JSONHandlerSync(response => {
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



const takeDownMessageWindow = $("#take-down-message");
const postTitle = takeDownMessageWindow.querySelector("#post-title-message");
const takeDownMessage = takeDownMessageWindow.querySelector("#message-container");
const takeDownMessageError = takeDownMessageWindow.querySelector(".error");
takeDownMessageWindow.addEventListener("fetch", async () => {
  takeDownMessageError.classList.remove("show");
  takeDownMessageError.textContent = "";
  
  postTitle.textContent = takeDownMessageWindow.dataset.postTitle;
  
  takeDownMessage.innerHTML = "<i>Loading...</i>";
  takeDownMessage.textContent = String(await AJAX.get(`/page/take-down/${takeDownMessageWindow.dataset.postID}/message`, TextHandler())
    .catch(() => {
      takeDownMessageError.classList.add("show");
      takeDownMessageError.textContent = "Could not retrieve take down message.";
      return "";
    }));
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
    AJAX.get(`/page/${index}/?type=${type}`, JSONHandlerSync(posts => {
      let element = undefined;
      
      for (const post of posts) {
        element = (
          PostComponent("page", post, [
            OptionBodyItem("View", {
              listeners: {
                click: () => redirect(createPostLink(post.website, post.src))
              }
            }),
            OptionBodyItem("Edit", {
              listeners: {
                click: () => redirect(AJAX.SERVER_HOME + "/editor/" + post.src)
              },
            }),
            OptionBodyItem("Delete", {
              listeners: {
                click: evt => {
                  AJAX.delete("/page/delete/" + post.src, JSONHandlerSync(response => {
                    if (response.error !== undefined) {
                      //TODO: create my own alert
                      alert(response.error);
                      return;
                    }
          
                    evt.target.closest(".post").remove();
                  }));
                }
              },
            }),
            ...OptionalComponents(post.isTakenDown,[
              OptionBodyItem("View appeal message", {
                listeners: {
                  click: () => {
                    const win = showWindow("take-down-message");
                    win.dataset.postID = String(post.ID);
                    win.dataset.postTitle = post.title;
                    win.dispatchEvent(new CustomEvent("fetch"));
                  }
                }
              }),
              OptionBodyItem("Appeal to take down", {
                listeners: {
                  click: () => {
                    postToAppealFor = post;
                    $("#post-title").textContent = post.title;
                    showWindow("appeal");
                  }
                }
              }),
            ])
          ])
        );
        
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
    const order = localStorage.getItem("file-order") ?? "0";
    
    AJAX.get(`/file/${index}/?order=${order}`, JSONHandlerSync(files => {
      let element = undefined;
  
      for (const file of files) {
        element = (
          Div("file", [
            Div(__, [
              Div("start", [
                FileIcon(file.mimeContentType),
                Span("selectable", file.basename, {
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
                        rejected(this.closest(".file > div"));
                        return;
                      }
              
                      const value = this.textContent;
                      const that = this;
                      AJAX.patch("/file/" + file.src, JSONHandlerSync(response => {
                        if (response.error) {
                          //TODO: custom alert
                          that.textContent = that.dataset.temporary;
                          rejected(that.closest(".file > div"));
                          alert(response.error);
                          return;
                        }
                
                        validated(that.closest(".file > div"));
                      }), {
                        body: JSON.stringify({ value })
                      });
                    },
                    keydown: contentEditableLimiter(200)
                  }
                }),
                Span(__, file.extension)
              ]),
              Div("end", [
                Span(__, fileSizeFormatter(file.size)),
                Button(__, "✕", evt => { //TODO: make into icon
                  AJAX.delete("/file/" + file.src, JSONHandlerSync(response => {
                    if (response.error) {
                      //TODO: create custom error alert
                      alert(response.error);
                      return;
                    }
            
                    evt.target.closest(".file").remove();
                  }));
                })
              ])
            ])
          ])
        );
        
        fileView.appendChild(element);
      }
      
      resolve(element);
    }));
  });
}
const filesInfiniteScroller = new InfiniteScroller(fileView, loadFiles);




// $("nav section button[title=Profile]").dispatchEvent(new CustomEvent("pointerdown"));









//* Gallery
const selectedFilesMap = new Map();
const selectedFiles = $(".selected-files");
function displaySelectedFiles () {
  selectedFiles.textContent = "";
  selectedFilesMap.forEach((file) => {
    const fileElement = (
      Div("selected-file", [
        Div("start", [
          FileIcon(file.type),
          Span(__, file.name),
        ]),
        Div("end", [
          Span(__, fileSizeFormatter(file.size)),
          Button(__, "✕", () => {
            selectedFilesMap.delete(file.lastModified);
            fileElement.remove();
          })
        ])
      ])
    );
    
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

  AJAX.post("/file/collect", JSONHandlerSync(files => {
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

changeUserPreferredSetting(".change-file-order", "order", "file-order", filesInfiniteScroller);