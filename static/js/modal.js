/** @type {Map<string, HTMLDivElement>} */
const windows = new Map();

const container = $(".modals");
for (const win of container.children) {
  windows.set(win.id, win);
  win.querySelectorAll(".cancel-modal").forEach(button => button.addEventListener("click", () => clearWindows()))
}

/**
 * @param {string} id
 */
function showWindow (id) {
  clearWindows(false);
  
  const win = windows.get(id);
  win.querySelectorAll("input").forEach(input => {
    switch (input.getAttribute("type")) {
      case "text": {
        input.value = "";
        break;
      }
      case "checkbox": {
        input.checked = false;
      }
    }
  });
  
  container.classList.add("darken");
  win.classList.add("show");
}

function clearWindows (includeDarken = true) {
  for (const win of container.children) {
    win.classList.remove("show");
  }
  
  if (includeDarken) {
    container.classList.remove("darken");
  }
}