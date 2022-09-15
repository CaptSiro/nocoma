const loginEmail = $("input#l-email");
const loginPassword = $("input#l-password");

const loginError = $(".form.login p.error");

const loginSubmit = $(".login button.submit");
loginSubmit.addEventListener("click", evt => {
  if (loginEmail.value == "" || loginPassword.value == "") {
    loginError.textContent = "All fields must be filled in.";
    loginError.classList.add("show");
    return;
  }
  
  AJAX.post(
    "read-login.php",
    toFormData({
      email: loginEmail.value,
      password: loginPassword.value
    }),
    json => {
      if (json.error !== undefined) {
        loginError.textContent = json.error;
        loginError.classList.add("show");
        return;
      }

      loginError.classList.remove("show");
      loginError.textContent = "";

      if (json.next !== undefined) {
        switchForm(loginSubmit.closest(".form"), json.next);

        if (json.next == "code-verification") {
          $("button.request-code").dispatchEvent(new Event("pointerdown"));
        }
        return;
      }

      window.location.replace(json.redirect);
    }
  );
});