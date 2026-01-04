document.addEventListener("DOMContentLoaded", function () {
  var form = document.getElementById("registerForm");
  var msg = document.getElementById("regMsg");

  function showMessage(text, type) {
    msg.classList.remove("msg-hidden");
    msg.classList.remove("msg-error", "msg-success");
    msg.classList.add(type === "success" ? "msg-success" : "msg-error");
    msg.textContent = text;
  }

  form.addEventListener("submit", function (e) {
    var name = document.getElementById("name").value.trim();
    var email = document.getElementById("email").value.trim();
    var phone = document.getElementById("phone").value.trim();
    var pass = document.getElementById("password").value.trim();
    var cpass = document.getElementById("confirm_password").value.trim();

    if (name === "" || email === "" || phone === "" || pass === "" || cpass === "") {
      e.preventDefault();
      showMessage("Please fill all fields.", "error");
      return;
    }

    if (pass.length < 4) {
      e.preventDefault();
      showMessage("Password should be at least 4 characters.", "error");
      return;
    }

    if (pass !== cpass) {
      e.preventDefault();
      showMessage("Password and Confirm Password do not match.", "error");
      return;
    }
  });
});
