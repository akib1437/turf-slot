// views/manager_views/js/script.js

function confirmDelete() {
  return confirm("Are you sure you want to delete this slot?");
}

function isTimeRangeValid(start, end) {
  // "HH:MM" strings compare correctly if same format
  return start && end && start < end;
}

document.addEventListener("DOMContentLoaded", function () {
  // Validate add slot form
  const addForm = document.getElementById("addSlotForm");
  if (addForm) {
    addForm.addEventListener("submit", function (e) {
      const start = document.getElementById("startTime").value;
      const end = document.getElementById("endTime").value;

      if (!start || !end) {
        e.preventDefault();
        alert("Please select start and end time.");
        return;
      }

      if (!isTimeRangeValid(start, end)) {
        e.preventDefault();
        alert("End time must be after start time.");
      }
    });
  }

  // Validate edit slot form
  const editForm = document.getElementById("editSlotForm");
  if (editForm) {
    editForm.addEventListener("submit", function (e) {
      const start = document.getElementById("startTime").value;
      const end = document.getElementById("endTime").value;

      if (!isTimeRangeValid(start, end)) {
        e.preventDefault();
        alert("End time must be after start time.");
      }
    });
  }
});
