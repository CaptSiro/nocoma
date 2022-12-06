//* login
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


window.addEventListener("load", evt => {
  AJAX.get("bg-images.php", images => {
    document.body.style.backgroundImage = `url(../public/images/login-register-bgs/${images[flatRNG(0, images.length - 1)]})`;
  });
});









//* register
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
};
$$(".form.register input[regex]").forEach(e => {
  const key = e.getAttribute("regex");
  const errorPar = e.nextElementSibling;
  e.addEventListener("focusout", evt => {
    const isValid = regexes[key].r.test(e.value);
    if (isValid === true) {
      errorPar.textContent = "";
      errorPar.classList.remove("show");
      e.classList.remove("invalid");
    } else {
      errorPar.innerHTML = regexes[key].err;
      errorPar.classList.add("show");
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



const tosCheckBox = $("check-box#r-tos-pp");
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

  // if (!tosCheckBox.isChecked()) {
  //   registerSubmit.nextElementSibling.innerHTML = "You must agree with terms of service and privacy policy.";
  //   registerSubmit.nextElementSibling.classList.add("show");
  //   return;
  // }

  registerSubmit.nextElementSibling.classList.remove("show");

  AJAX.post(
    "create-register.php",
    toFormData(body),
    json => {
      const err = registerSubmit.nextElementSibling;
      if (json.error !== undefined) {
        err.textContent = "Server: " + json.error;
        err.classList.add("show");
      } else {
        err.textContent = "";
        err.classList.remove("show");

        switchForm(registerSubmit.closest(".form"), json.next);

        $("button.request-code").dispatchEvent(new Event("pointerdown"));
      }
    }
  );
});










//* forgotten password
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






//* verification
const requestCode = $("button.request-code");
const requestTimer = new Timer(60);
requestTimer.onChange(num => {
  requestCode.textContent = `You may request code again in (${num}).`;
});
requestTimer.onFinish(() => {
  requestCode.textContent = `You may request code again.`;
});



const verificationError = $(".code-verification p.error");
requestCode.addEventListener("pointerdown", evt => {
  console.log("click");
  if (requestTimer.isFinished()) {
    AJAX.get(
      "request-code.php",
      json => {
        if (json.error !== undefined) {
          verificationError.textContent = json.error;
          verificationError.classList.add("show");
          return;
        }

        verificationError.classList.remove("show");
        verificationError.textContent = "";
      },
      {
        credentials: "include"
      }
    );

    requestTimer.reset();
  }
});

const errorVerification = $(".code-verification p.error");
const verificationSubmit = $(".code-verification button.submit");
verificationSubmit.addEventListener("pointerdown", evt => {
  const code = getVarificationValue();
  if (code == "") {
    errorVerification.textContent = "Please fill code field.";
    return;
  }

  AJAX.post(
    "update-verify.php",
    toFormData({code}),
    json => {
      if (json.error !== undefined) {
        verificationError.textContent = json.error;
        verificationError.classList.add("show");
        return;
      }

      verificationError.classList.remove("show");
      verificationError.textContent = "";

      if (json.redirect !== undefined) {
        window.location.replace(json.redirect);
      }
    }
  )
});