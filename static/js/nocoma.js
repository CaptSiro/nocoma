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




const usersThemesPromise = Theme.getUsers("/theme/user/all");
Theme.get("/theme/user")
  .then(async theme => {
    const themeSelect = $("#themes-showcase");
    const leftArrow = $("#themes > .left-arrow");
    const rightArrow = $("#themes > .right-arrow");
    let currentChild = 0;
    
    const userThemes = await usersThemesPromise;
  
    for (const userTheme of userThemes) {
      themeSelect.appendChild(
        Div(__,
          Heading(3, __, userTheme.name),
          { attributes: { "data-source": /* AJAX.SERVER_HOME + "/theme/" + */userTheme.src } }
        )
      );
    }
    
    leftArrow.addEventListener("pointerdown", async () => {
      if (currentChild === 0) return;
    
      rightArrow.classList.remove("hide");
      currentChild--;
      await Theme.setAsLink(themeSelect.children[currentChild].dataset.source);
      themeSelect.scrollTo(currentChild * 200, 0);
    
      if (currentChild === 0) {
        leftArrow.classList.add("hide");
      }
  
      await sleep(50);
      assignColors();
    });
    rightArrow.addEventListener("pointerdown", async () => {
      if (currentChild === themeSelect.children.length - 1) return;
    
      leftArrow.classList.remove("hide");
      currentChild++;
      await Theme.setAsLink(themeSelect.children[currentChild].dataset.source);
      themeSelect.scrollTo(currentChild * 200, 0);
    
      if (currentChild === themeSelect.children.length - 1) {
        rightArrow.classList.add("hide");
      }
      
      await sleep(50);
      assignColors();
    });
  
    let index = 0;
    for (const themeOption of themeSelect.children) {
      if (themeOption.dataset.source.endsWith(theme.src)) {
        currentChild = index;
        break;
      }
    
      index++;
    }
  
    themeSelect.scrollTo(currentChild * 200, 0);
    
    if (currentChild === 0) {
      leftArrow.classList.add("hide");
    }
    
    if (currentChild === themeSelect.children.length - 1) {
      rightArrow.classList.add("hide");
    }
    
    await sleep(50);
    assignColors();
  });