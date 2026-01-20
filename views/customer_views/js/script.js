// views/customer_views/js/script.js

document.addEventListener("DOMContentLoaded", function () {
  // Profile validation
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

  // Booking request validation
  const bookingForm = document.getElementById("bookingForm");
  if (bookingForm) {
    bookingForm.addEventListener("submit", function (e) {
      const team = document.getElementById("teamName").value.trim();
      const phone = document.getElementById("phone").value.trim();

      if (!team || !phone) {
        e.preventDefault();
        alert("Please fill team name and phone.");
      }
    });
  }
});
function confirmCancel() {
  return confirm("Cancel this booking request? Only Pending can be cancelled.");
}
