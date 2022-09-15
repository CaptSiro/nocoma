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