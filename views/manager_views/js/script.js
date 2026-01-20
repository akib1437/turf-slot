// views/manager_views/js/script.js
document.addEventListener("DOMContentLoaded", function () {
  const profileForm = document.getElementById("profileForm");
  if (profileForm) {
    profileForm.addEventListener("submit", function (e) {
      const name = document.getElementById("pName").value.trim();
      const phone = document.getElementById("pPhone").value.trim();

      if (!name || !phone) {
        e.preventDefault();
        alert("Name and phone are required.");
      }
    });
  }
});
