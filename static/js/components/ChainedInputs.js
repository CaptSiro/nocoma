/**
 * Adds logic for chained inputs.
 * 
 * If input's value overflows maximum length the cursor gets placed on next input.
 * 
 * If there is nothing to be deleted in current input the cursor gets placed back.
 * 
 * Maximum length of input's value can be defined for whole group (1) individually (2).
 * 
 * If max length is not defined then `1` is used as max length.
 * 
 * ```html
 * <div class="chained-inputs" max-length="3"> <!-- 1 -->
 *  ...
 *  <input type="number" max-length="5"> <!-- 2 -->
 *  ...
 * </div>
 * ```
 * @param {HTMLElement} inputsContainer
 * @returns {()=>string} Value getter.
 */
function chainedInputs (inputsContainer) {
  const font = (window.getComputedStyle(inputsContainer).getPropertyValue("font-weight") ?? "normal")
  + " " + (window.getComputedStyle(inputsContainer).getPropertyValue("font-size") ?? "16px")
  + " " + (window.getComputedStyle(inputsContainer).getPropertyValue("font-family") ?? "Times Nex Roman");

  const ctx = document.createElement("canvas").getContext("2d");
  ctx.font = font;
  const charWidth = Math.round(ctx.measureText("M").width);



  const children = Array.from(inputsContainer.children);
  const conMaxLength = inputsContainer.getAttribute("max-length");
  const baseMaxLength = (conMaxLength === null)
    ? 1
    : conMaxLength;


  for (const element of children) {
    const inputMaxLength = element.getAttribute("max-length");
    const maxLength = (inputMaxLength === null)
      ? Number(baseMaxLength)
      : Number(inputMaxLength);

    
    element.style.width = (maxLength * charWidth) + "px";
    element.style.boxSizing = "content-box";

    element.addEventListener("paste", evt => {
      evt.stopPropagation();
      evt.preventDefault();
      const pasted = (evt.clipboardData || clipboardData).getData("Text");

      if (/^[0-9]{6}$/.test(pasted)) {
        let char = 0;
        for (const element of children) {
          element.value = pasted[char++];
        }

        children[children.length - 1].focus();
      }
    });

    element.addEventListener("input", evt => {
      if (element.value?.length === maxLength && element.nextElementSibling !== null) {
        element.nextElementSibling.focus();
      }

      if (element.value?.length > maxLength) {
        element.value = element.value.substring(0, maxLength);
      }
    });

    element.addEventListener("keydown", evt => {
      if (evt.key === "Backspace" && element.value?.length === 0 && element.previousElementSibling !== null) {
        const prev = element.previousElementSibling;
        if (prev.value !== undefined || prev.value === null) {
          prev.value = prev.value.substring(0, prev.value.length - 1);
          evt.preventDefault();
          element.blur();
          prev.focus();
        }
      }
    });
  }

  return () => (children.reduce((acc, e) => (acc + e.value), ""));
}