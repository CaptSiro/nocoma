//* login
const loginEmail = $("input#l-email");
const loginPassword = $("input#l-password");

const loginError = $(".form.login p.error");

const loginSubmit = $(".login button.submit");
loginSubmit.addEventListener("click", evt => {
  if (loginEmail.value === "" || loginPassword.value === "") {
    loginError.textContent = "All fields must be filled in.";
    loginError.classList.add("show");
    return;
  }
  
  AJAX.post("/auth/login", JSONHandlerSync(json => {
    if (json.error !== undefined) {
      loginError.textContent = json.error;
      loginError.classList.add("show");
      return;
    }
  
    loginError.classList.remove("show");
    loginError.textContent = "";
    
    if (json.redirect !== undefined) {
      window.location.replace(json.redirect);
      return;
    }
  
    switchForm(loginSubmit.closest(".form"), json.next);
    if (json.next === "code-verification") {
      $("button.request-code").dispatchEvent(new Event("pointerdown"));
    }
  }), {
    body: JSON.stringify({
      email: loginEmail.value,
      password: loginPassword.value
    })
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
    r: new RegExp("^[a-zA-Z0-9-_]{1," + Math.min(255 - host.length, 63) + "}$"),
    err: "Website may contain only letters, numbers, dashes or underscores. Maximum length is " + Math.min(255 - host.length, 63) + " characters. (abcABC-_)"
  },
  password: {
    r: /(?=.{8,})(?=.*[a-zA-Z]+)(?=.*[0-9]+)(?=.*[!"#$%&'()*+,-./:;<=>?@\\^_\[\]`{|}~]+)^[a-zA-Z0-9!"#$%&'()*+,-./:;<=>?@\\^_\[\]`{|}~]+$/,
    err: "Password is not strong enough. At least 8 characters long, must contain capital/lowercase letters, numbers and at least one special character."
  },
  username: {
    r: /^[a-zA-Z0-9][a-zA-Z0-9 #$%&'()*+,-.:;^_~]+$/,
    err: "Not a valid username."
  }
};
$$(".form.register input[regex]").forEach(element => {
  const key = element.getAttribute("regex");
  const errorView = element.nextElementSibling;
  
  element.addEventListener("focusout", evt => {
    const isValid = regexes[key].r.test(element?.value);
    
    if (isValid === false) {
      errorView.innerHTML = regexes[key].err;
      errorView.classList.add("show");
      element.classList.add("invalid");
      return;
    }
  
    errorView.textContent = "";
    errorView.classList.remove("show");
    element.classList.remove("invalid");
  });
});




const registerPW = $("#r-password");
const registerPWAgain = $("#r-password-again");
const samePasswords = () => {
  registerPWAgain.classList.toggle("invalid", registerPW.value !== registerPWAgain.value);
}
registerPWAgain.addEventListener("input", samePasswords);
registerPW.addEventListener("input", samePasswords)





const inputWebsite = $("#r-website");
const yourWebsite = $("#your-website");
inputWebsite.addEventListener("input", evt => yourWebsite.textContent = inputWebsite.value);



const tosCheckBox = $("#r-tos-pp");
const registerSubmit = $(".form.register button.submit");
const registerErrorView = registerSubmit.nextElementSibling;
const inputs = {
  email: $("#r-email"),
  website: $("#r-website"),
  password: $("#r-password"),
  passwordAgain: $("#r-password-again"),
  username: $("#r-username")
}

registerSubmit.addEventListener("click", evt => {
  const body = {};
  for (const key in inputs) {
    if (inputs[key].value === "" || inputs[key].classList.contains("invalid")) {
      return;
    }
    
    body[key] = inputs[key].value;
  }

  if (!tosCheckBox.checked) {
    registerErrorView.innerHTML = "You must agree with terms of service and privacy policy.";
    registerErrorView.classList.add("show");
    return;
  }

  registerErrorView.classList.remove("show");
  
  alert("registered");
  return;

  AJAX.post("/auth/register", JSONHandlerSync(json => {
    if (json.error !== undefined) {
      registerErrorView.textContent = "Server: " + json.error;
      registerErrorView.classList.add("show");
      return;
    }
    
    registerErrorView.textContent = "";
    registerErrorView.classList.remove("show");
  
    switchForm(registerSubmit.closest(".form"), json.next);
  
    $("button.request-code").dispatchEvent(new Event("pointerdown"));
  }), {
    body: JSON.stringify(body)
  });
});










//* forgotten password
const forgorEmail = $("input#f-email");
const forgorError = $(".form.forgotten-password .error");
const forgorSubmit = $(".form.forgotten-password button.submit");

forgorSubmit.addEventListener("click", evt => {
  if (forgorEmail.value === "") {
    forgorError.textContent = "Email field must be filled in.";
    forgorError.classList.add("show");
    return;
  }
  
  AJAX.post("/auth/password-recovery-email", JSONHandlerSync(json => {
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
  }), {
    body: JSON.stringify({
      email: forgorEmail.value
    })
  });
});






//* verification
const requestCode = $("button.request-code");
const requestTimer = new Timer(60);
requestTimer.onChange(number => {
  requestCode.textContent = `You may request code again in (${number}).`;
});
requestTimer.onFinish(() => {
  requestCode.textContent = `You may request code again.`;
});



const verificationError = $(".code-verification p.error");
requestCode.addEventListener("pointerdown", evt => {
  if (!requestTimer.isFinished()) {
    return;
  }
  
  AJAX.get("/auth/verification-code", JSONHandlerSync(json => {
    if (json.error !== undefined) {
      verificationError.textContent = json.error;
      verificationError.classList.add("show");
      return;
    }
  
    verificationError.classList.remove("show");
    verificationError.textContent = "";
  }), {
    credentials: "include"
  });

  requestTimer.reset();
});

const errorVerification = $(".code-verification p.error");
const verificationSubmit = $(".code-verification button.submit");
verificationSubmit.addEventListener("pointerdown", evt => {
  const code = getVerificationValue();
  const invalidCode = code === "" || code.length !== 6;
  if (invalidCode) {
    errorVerification.textContent = "Please fill code field.";
    return;
  }
  
  AJAX.patch("/auth/verification", JSONHandlerSync(json => {
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
  }), {
    body: JSON.stringify({code})
  });
});