const newPassword = $("#new-password");
const newPasswordAgain = $("#new-password-again");
const submitButton = $("button.submit");
const errorView = $("#password-recovery-error");

const passwordRegex = /(?=.{8,})(?=.*[a-zA-Z]+)(?=.*[0-9]+)(?=.*[!"#$%&'()*+,-./:;<=>?@\\^_\[\]`{|}~]+)^[a-zA-Z0-9!"#$%&'()*+,-./:;<=>?@\\^_\[\]`{|}~]+$/;
const argumentRegex = /.*\/([0-9a-zA-Z_-]+)$/;
const argument = argumentRegex.exec(window.location.pathname)[1];

const sameValues = (...elements) => {
  return () => {
    const valuesAreSame = elements.reduce((set, element) => set.add(element.value), new Set()).size !== 1;
    elements.forEach(element => element.classList.toggle("invalid", valuesAreSame));
  }
}
newPassword.addEventListener("input", sameValues(newPassword, newPasswordAgain));
newPasswordAgain.addEventListener("input", sameValues(newPassword, newPasswordAgain));

submitButton.addEventListener("pointerdown", evt => {
  console.log("click?")
  if (
    newPassword.value === "" || newPassword.classList.contains("invalid") ||
    newPasswordAgain.value === "" || newPasswordAgain.classList.contains("invalid")
  ) {
    return;
  }
  
  if (!passwordRegex.test(newPassword.value)) {
    errorView.textContent = "Password is not strong enough. At least 8 characters, one of which is capital and at least on special character.";
    errorView.classList.add("show");
    newPassword.classList.add("invalid");
    return;
  }
  
  errorView.textContent = "";
  errorView.classList.remove("show");
  newPassword.classList.remove("invalid");
  
  AJAX.patch("/auth/password", JSONHandlerSync(json => {
    if (json.error !== undefined) {
      errorView.textContent = json.error;
      errorView.classList.add("show");
      newPassword.classList.add("invalid");
      return;
    }
    
    if (json.next !== undefined) {
      errorView.textContent = "";
      errorView.classList.remove("show");
      newPassword.classList.remove("invalid");
      switchForm(newPassword.closest(".form"), json.next);
      return;
    }
  
    console.log(json);
  }), {
    body: JSON.stringify({
      password: newPassword.value,
      argument
    })
  });
});