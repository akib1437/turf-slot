document.addEventListener("DOMContentLoaded", function () {
  var form = document.getElementById("loginForm");
  var msg = document.getElementById("loginMsg");

  function showMessage(text, type) {
    msg.classList.remove("msg-hidden");
    msg.classList.remove("msg-error", "msg-success");
    msg.classList.add(type === "success" ? "msg-success" : "msg-error");
    msg.textContent = text;
  }

  form.addEventListener("submit", function (e) {
    var email = document.getElementById("email").value.trim();
    var pass = document.getElementById("password").value.trim();

    if (email === "" || pass === "") {
      e.preventDefault();
      showMessage("Please enter email and password.", "error");
      return;
    }
  });
});
