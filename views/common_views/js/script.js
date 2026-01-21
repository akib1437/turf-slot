
function showAlert(message) {
  alert(message);
}

document.addEventListener("DOMContentLoaded", function () {
  // Login form validation
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      const email = document.getElementById("loginEmail").value.trim();
      const pass = document.getElementById("loginPassword").value.trim();

      if (email === "" || pass === "") {
        e.preventDefault();
        showAlert("Please enter email and password.");
      }
    });
  }

  // Register form validation
  const registerForm = document.getElementById("registerForm");
  if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
      const name = document.getElementById("regName").value.trim();
      const email = document.getElementById("regEmail").value.trim();
      const phone = document.getElementById("regPhone").value.trim();
      const pass = document.getElementById("regPassword").value.trim();
      const role = document.getElementById("regRole").value;

      if (!name || !email || !phone || !pass || !role) {
        e.preventDefault();
        showAlert("Please fill all fields.");
        return;
      }

      if (pass.length < 6) {
        e.preventDefault();
        showAlert("Password must be at least 6 characters.");
        return;
      }
    });
  }
});
