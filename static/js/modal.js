/** @type {Map<string, HTMLDivElement>} */
const windows = new Map();

const container = $(".modals");
for (const win of container.children) {
  windows.set(win.id, win);
  const errors = win.querySelectorAll(".error-modal");
  win.querySelectorAll(".cancel-modal").forEach(button => {
    button.addEventListener("click", () => {
      clearWindows();
      errors.forEach(error => {
        error.textContent = "";
        error.classList.remove("show");
      });
    });
  });
}

/**
 * @param {string} id
 * @returns {HTMLDivElement}
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
  
  return win;
}

function clearWindows (includeDarken = true) {
  for (const win of container.children) {
    win.classList.remove("show");
  }
  
  if (includeDarken) {
    container.classList.remove("darken");
  }
}