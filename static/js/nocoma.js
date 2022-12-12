/** @type {Renderer3D} */
const renederer = document.querySelector("#animator-3d");
new IntersectionObserver(entries => {
  if (entries[0].isIntersecting === true) {
    renederer.resumeRender();
  } else {
    renederer.stopRender();
  }
}, { threshold: 0 }).observe(renederer);




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


const colors = ["rgb(69, 20, 93)", "rgb(120, 37, 131)", "rgb(177, 59, 165)"];
const cubes = [Object3D.createCube("cube-1", 100), Object3D.createCube("cube-2", 75), Object3D.createCube("cube-3", 50)];

let i = 0;
for (const cube of cubes) {
  cube.moveTo(0, i * 50, 0);
  cube.setColor(colors[i]);

  cube.setAnimation(animationFactory(i * 100, cube, i * 50, 20 + i * 5));
  i++;
}

renederer.addEventListener("load", _ => {
  renederer.addObjects(...cubes);
  renederer.mainCamera.lockMovement();
  renederer.mainCamera.moveBy(-30, 40, 0);
});





document.querySelectorAll(".login-button").forEach(button => {
  button.addEventListener("click", () => {
    window.location.replace(HOME + "/auth/");
  });
});