const passwordRegex = {
  r: /(?=.{8,})(?=.*[a-zA-Z]+)(?=.*[0-9]+)(?=.*[%_&@]+)^[a-zA-Z0-9&%@_]+$/,
  err: "Password is not strong enough. At least 8 characters long, must contain capital/lowercase letters, numbers and one of special characters (&%@_)."
};


if ($$(".form").length > 1) {
  const recoveryPW = $("input#n-password");
  const recoveryPWError = recoveryPW.nextElementSibling;
  recoveryPW.addEventListener("focusout", evt => {
    const isValid = passwordRegex.r.test(recoveryPW.value);
    if (isValid === true) {
      recoveryPWError.textContent = "";
      recoveryPWError.classList.remove("show");
      recoveryPW.classList.remove("invalid");
    } else {
      recoveryPWError.innerHTML = passwordRegex.err;
      recoveryPWError.classList.add("show");
      recoveryPW.classList.add("invalid");
    }
  });
  
  
  const recoveryPWAgain = $("input#n-password-again");
  const samePasswords = () => {
    if (recoveryPW.value === recoveryPWAgain.value) {
      recoveryPWAgain.classList.remove("invalid");
    } else {
      recoveryPWAgain.classList.add("invalid");
    }
  }
  
  recoveryPW.addEventListener("input", samePasswords);
  recoveryPWAgain.addEventListener("input", samePasswords);
  
  
  
  
  const recoverySubmit = $(".form.new-password button.submit");
  const recoveryError = $(".form.new-password p.error");
  const urlParams = new URLSearchParams(window.location.search);
  recoverySubmit.addEventListener("pointerdown", evt => {
    const inputs = [recoveryPW, recoveryPWAgain];
  
    for (const input of inputs) {
      if (input.value == "" || input.classList.contains("invalid")) {
        return;
      }
    }
  
    AJAX.post(
      "update-password.php",
      toFormData({
        password: recoveryPW.value,
        urlArg: urlParams.get("prid")
      }),
      json => {
        if (json.error !== undefined) {
          recoveryError.classList.add("show");
          recoveryError.textContent = json.error;
          return;
        }
  
        if (json.next !== undefined) {
          switchForm(recoverySubmit.closest(".form"), json.next);
          
          recoveryError.textContent = "";
          recoveryError.classList.remove("show");
        }
      }
    );
  });
}