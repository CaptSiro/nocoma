/** @type {Map<string, HTMLDivElement>} */
const forms = new Map();

$$(".form").forEach(e => {
  forms.set(e.classList.item(1), e);
});

$$("button[link-to]").forEach(e => {
  const linkTo = e.getAttribute("link-to");
  e.addEventListener("pointerdown", evt => {
    e.closest(".form").classList.add("hide");
    forms.get(linkTo).classList.remove("hide");
  });
});