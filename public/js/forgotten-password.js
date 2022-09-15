const forgorEmail = $("input#f-email");
const forgorError = $(".form.forgotten-password .error");
const forgorSubmit = $(".form.forgotten-password button.submit");

forgorSubmit.addEventListener("click", evt => {
  if (forgorEmail.value == "") {
    forgorError.textContent = "Email field must be filled in.";
    forgorError.classList.add("show");
    return;
  }

  AJAX.post(
    "read-forgor-password-email.php",
    toFormData({ email: forgorEmail.value }),
    json => {
      if (json.error !== undefined) {
        forgorError.classList.add("show");
        forgorError.textContent = json.error;
        return;
      }

      if (json.next !== undefined) {
        switchForm(forgorSubmit.closest(".form"), json.next);
        forgorError.textContent = "";
        forgorError.classList.remove("show");
      }
    }
  )
});