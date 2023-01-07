/** @type {Object.<string, HTMLDivElement>} */
const dictionary = {};
document.querySelectorAll(".link-element").forEach(e => {
  dictionary[e.id] = e;
});



/**
 * @param {Element} invoker 
 * @param {NodeListOf<Element>} invokers 
 */
function point (invoker, invokers) {
  const id = invoker.getAttribute("reference-to");

  if (id === null) return () => {
    console.error("The following element does not have attribute named 'link-to'.")
    console.log(invoker);
  }

  return () => {
    for (const inv of invokers) {
      inv.classList.remove("active");
    }

    invoker.classList.add("active");

    if (dictionary[id] === undefined) {
      console.error("Not valid value of 'link-to': " + id);
    }

    activeID = id;
    dictionary[id].scrollIntoView({ behavior: "smooth" });
  }
}
/** @type {string} */
let activeID;
window.addEventListener("resize", _ => dictionary[activeID]?.scrollIntoView({ behavior: "smooth" }));
document.querySelectorAll(".link-pointer").forEach((p, _, arr) => p.addEventListener("pointerdown", point(p, arr)))



// logout user
$("#logout").addEventListener("click", () => {
  AJAX.delete("/auth/logout", JSONHandlerSync(json => {
    window.location.replace(json.redirect);
  }));
});




//* user section
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
  AJAX.patch("/profile/username/", JSONHandlerSync(response => {
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
  AJAX.post("/auth/password-recovery-email", JSONHandlerSync(json => {
    if (json.error !== undefined) {
      //TODO: custom alert
      alert(json.error);
      return;
    }
    
    alert("Email has been sent with link to password reset form.");
  }));
});

const profilePicture = $("#profile-picture");
const profilePictureSmall = $("#profile-picture-small");
let profilePictureIndex = 0;
$("#profile-picture-upload").addEventListener("change", evt => {
  AJAX.post("/profile/picture/", JSONHandlerSync(response => {
    if (response.error) {
      console.log(evt.target.closest("section.info"))
      rejected(evt.target.closest("section.info"));
      //TODO: custom alert
      console.log(response.error);
      return;
    }
    
    validated(evt.target.closest("section.info"));
    profilePicture.src = AJAX.SERVER_HOME + "/profile/picture";
    profilePictureSmall.style.backgroundImage = `url(${AJAX.SERVER_HOME}/profile/picture/?index=${profilePictureIndex++})`;
  }), {
    body: toFormData({
      picture: evt.target.files[0]
    })
  });
});


/**
 * @param {string} css
 * @param {string} datasetPropertyName
 * @param {string} storageKey
 * @param {InfiniteScroller|undefined} scroller
 */
function changeUserPreferredSetting (css, datasetPropertyName, storageKey, scroller = undefined) {
  const listener = (button) => (() => {
    if (localStorage.getItem(storageKey) === button.dataset[datasetPropertyName]) return;
  
    localStorage.setItem(storageKey, button.dataset[datasetPropertyName]);
    
    if (scroller !== undefined) {
      scroller.reset();
    }
  });
  
  $$(css).forEach(button => {
    button.addEventListener("click", listener(button));
    button.addEventListener("pointerdown", listener(button));
    button.addEventListener("submit", listener(button));
  });
}