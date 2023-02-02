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
    profilePicture.src = AJAX.SERVER_HOME + `/profile/picture/?index=${profilePictureIndex++}`;
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





const themeSwitcher = $("#theme-select");
window.addEventListener("click", () => themeSwitcher.classList.remove("expand"))
themeSwitcher.addEventListener("click", evt => {
  themeSwitcher.classList.add("expand");
  evt.stopImmediatePropagation();
});
themeSwitcher.style.setProperty("--height", ((window.innerHeight - 40) - themeSwitcher.getBoundingClientRect().bottom) + "px");
const themeLabel = themeSwitcher.querySelector("#theme-name");
const themeContent = themeSwitcher.querySelector(".content");

let firstConditionMet = false;

window.addEventListener("themesLoaded", makeThemeVisible);
window.addEventListener("themeSelect", makeThemeVisible);
function makeThemeVisible () {
  if (firstConditionMet === false) {
    firstConditionMet = true;
    return;
  }
  
  const themeSource = sessionStorage.getItem("themesSRC");
  
  Array.from(themeContent.children)
    .forEach(option => {
      if (!option.dataset.value.endsWith(themeSource)) return;
      themeSwitcher.value = option.dataset.value;
      themeLabel.innerText = option.innerText;
    });
  
  window.removeEventListener("themesLoaded", makeThemeVisible);
  window.removeEventListener("themeSelect", makeThemeVisible);
}


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
AJAX.get("/theme/user/all-v2", JSONHandlerSync(themes => {
  if (themes.error) {
    console.log(themes);
    return;
  }
  
  themeContent.append(
    ...themes
      .map(raw => ThemeColor(parseTheme(raw), themeSwitcher))
  );
  
  window.dispatchEvent(new CustomEvent("themeSelect"));
}));

themeSwitcher.addEventListener("change", async () => {
  const themeResponse = await AJAX.patch("/profile/theme-src", JSONHandler(), {
    body: JSON.stringify({
      src: themeSwitcher.value.substring(themeSwitcher.value.length - 8)
    })
  });
  
  if (themeResponse.error !== undefined) {
    console.log(themeResponse);
    return;
  }
  
  const themeLink = $(".themes-link");
  const newThemeLink = Component("link", "theme-link", __, {
    attributes: {
      id: "themes-link",
      rel: "stylesheet",
      href: themeSwitcher.value
    }
  });
  
  for (const option of themeContent.children) {
    if (option.dataset.value === themeSwitcher.value) {
      themeLabel.innerText = option.innerText;
      break;
    }
  }
  
  document.head.appendChild(newThemeLink);
  newThemeLink.addEventListener("load", async () => {
    await sleep(50);
    themeLink?.remove();
  });
});