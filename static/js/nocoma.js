/** @type {Renderer3D} */
const renderer = document.querySelector("#animator-3d");
new IntersectionObserver(entries => {
  if (entries[0].isIntersecting === true) {
    renderer.resumeRender();
  } else {
    renderer.stopRender();
  }
}, { threshold: 0 }).observe(renderer);




/**
 * 
 * @param {number} stagger 
 * @param {Object3D} object3d 
 * @param {(number: dt)=>Rotation} rotationBuilder 
 */
function animationFactory (stagger, object3d, ypos, yrange) {
  let localAcc = stagger;

  return dt => {
    localAcc += dt / 1000 * 2;

    object3d.moveTo(0, ypos + Math.sin(localAcc) * yrange, 0);
    object3d.rotateTo(Rotation.fromDeg((localAcc * 45 / 2) + stagger / 10, 0, 0));
  }
}


let colors = ["rgb(69, 20, 93)", "rgb(120, 37, 131)", "rgb(177, 59, 165)"];
const cubes = [Object3D.createCube("cube-1", 100), Object3D.createCube("cube-2", 75), Object3D.createCube("cube-3", 50)];

let i = 0;
for (const cube of cubes) {
  cube.moveTo(0, i * 50, 0);
  cube.setWireFrameSize(5);

  cube.setAnimation(animationFactory(i * 100, cube, i * 50, 20 + i * 5));
  i++;
}

renderer.addEventListener("load", _ => {
  assignColors();
  
  renderer.addObjects(...cubes);
  renderer.mainCamera.lockMovement();
  
  // renderer.load("http://localhost/nocoma/public/megumin.obj");
  renderer.mainCamera.moveBy(-30, 40, 0);
});

function assignColors () {
  const computed = getComputedStyle(document.documentElement);
  
  colors = [
    computed.getPropertyValue("--text-color-0"),
    computed.getPropertyValue("--container-opposite-3"),
    computed.getPropertyValue("--text-color-2"),
  ];
  
  let i = 0;
  for (const color of colors) {
    cubes[i].setColor(color);
    i++;
  }
}





document.querySelectorAll(".login-button").forEach(button => {
  button.addEventListener("click", () => {
    history.pushState({}, '', new URL(window.location));
    window.location.replace(HOME + "/auth/");
  });
});




const themeSwitcher = $("#themes-showcase");
const leftArrow = $("#themes > .left-arrow");
const rightArrow = $("#themes > .right-arrow");
let firstConditionMet = false;

window.addEventListener("themesLoaded", makeThemeVisible);
window.addEventListener("themeSelect", makeThemeVisible);
let currentChild = 0;
function makeThemeVisible () {
  if (firstConditionMet === false) {
    firstConditionMet = true;
    return;
  }
  
  const currentThemeSource = sessionStorage.getItem("themesSRC");
  let index = 0;
  for (const theme of themeSwitcher.children) {
    if (theme.dataset.source.endsWith(currentThemeSource)) {
      currentChild = index;
      break;
    }
    
    index++;
  }
  
  themeSwitcher.scrollTo(currentChild * 200, 0);
  leftArrow.classList.remove("hide");
  rightArrow.classList.remove("hide");
  if (currentChild === 0) {
    leftArrow.classList.add("hide");
  }
  if (currentChild === themeSwitcher.children.length - 1) {
    rightArrow.classList.add("hide");
  }
  
  window.removeEventListener("themesLoaded", makeThemeVisible);
  window.removeEventListener("themeSelect", makeThemeVisible);
}

AJAX.get("/theme/user/all", JSONHandlerSync(themes => {
  for (const theme of themes) {
    themeSwitcher.appendChild(
      Div(__,
        Heading(3, __, theme.name),
        { attributes: { "data-source": AJAX.SERVER_HOME + "/theme/" + theme.src } }
      )
    );
  }
  
  window.dispatchEvent(new CustomEvent("themeSelect"));
  
  leftArrow.classList.add("hide");
  leftArrow.addEventListener("pointerdown", () => {
    if (currentChild === 0) return;
    
    rightArrow.classList.remove("hide");
    currentChild--;
    setTheme(themeSwitcher.children[currentChild].dataset.source);
    themeSwitcher.scrollTo(currentChild * 200, 0);
    
    if (currentChild === 0) {
      leftArrow.classList.add("hide");
    }
  });
  
  rightArrow.addEventListener("click", () => {
    if (currentChild === themeSwitcher.children.length - 1) return;
  
    leftArrow.classList.remove("hide");
    currentChild++;
    setTheme(themeSwitcher.children[currentChild].dataset.source);
    themeSwitcher.scrollTo(currentChild * 200, 0);
  
    if (currentChild === themeSwitcher.children.length - 1) {
      rightArrow.classList.add("hide");
    }
  });
}));

function setTheme (source) {
  const themeLink = $(".themes-link");
  const newThemeLink = Component("link", "theme-link", __, {
    attributes: {
      id: "themes-link",
      rel: "stylesheet",
      href: source
    }
  });
  document.head.appendChild(newThemeLink);
  newThemeLink.addEventListener("load", () => {
    setTimeout(() => {
      assignColors();
      themeLink?.remove();
    })
  });
}