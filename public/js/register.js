new TextSlider($(".url-website"), { gapSize: 50 });



// regex checks
const regexes = {
  email: {
    r: /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/,
    err: "Not a valid email address."
  },
  website: {
    r: /^[a-zA-Z0-9-_]{1,64}$/,
    err: "Website may contain only letters, numbers, dashes or undescores. Maximum length is 64 characters. (abcABC-_)"
  },
  password: {
    r: /(?=.{8,})(?=.*[a-zA-Z]+)(?=.*[0-9]+)(?=.*[%_&@]+)^[a-zA-Z0-9&%@_]+$/,
    err: "Password is not strong enough. At least 8 characters long, must contain capital/lowercase letters, numbers and one of special characters (&%@_)."
  }
}
$$(".form.register input[regex]").forEach(e => {
  const key = e.getAttribute("regex");
  const errorPar = e.nextElementSibling;
  e.addEventListener("focusout", evt => {
    const isValid = regexes[key].r.test(e.value);
    if (isValid === true) {
      errorPar.textContent = "";
      e.classList.remove("invalid");
    } else {
      errorPar.innerHTML = regexes[key].err;
      e.classList.add("invalid");
    }
  });
});




const registerPW = $("#r-password");
const registerPWAgain = $("#r-password-again");
const samePasswords = () => {
  if (registerPW.value === registerPWAgain.value) {
    registerPWAgain.classList.remove("invalid");
  } else {
    registerPWAgain.classList.add("invalid");
  }
}
registerPWAgain.addEventListener("input", samePasswords);
registerPW.addEventListener("input", samePasswords)





const inputWebsite = $("#r-website");
const yourWebsite = $("#your-website");
inputWebsite.addEventListener("input", evt => yourWebsite.textContent = inputWebsite.value);




const registerSubmit = $(".form.register button.submit");
registerSubmit.addEventListener("click", evt => {
  const inputs = {
    email: $("#r-email"),
    website: $("#r-website"),
    password: $("#r-password"),
    passwordAgain: $("#r-password-again")
  }

  const body = {};
  for (const key in inputs) {
    if (inputs[key].value === "" || inputs[key].classList.contains("invalid")) {
      return;
    } else {
      body[key] = inputs[key].value;
    }
  }

  AJAX.post(
    "create-register.php",
    toFormData(body),
    json => {
      if (json.error !== undefined) {
        registerSubmit.nextElementSibling.textContent = "Server: " + json.error;
      } else {
        registerSubmit.nextElementSibling.textContent = "";
        registerSubmit.closest(".form").classList.add("hide");
        forms.get(json.next).classList.remove("hide");

        $("button.request-code").dispatchEvent(new Event("pointerdown"));
      }
    }
  );
});