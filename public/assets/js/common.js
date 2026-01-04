document.addEventListener("DOMContentLoaded", function () {
  // Active nav link highlight (simple)
  var links = document.querySelectorAll(".nav-link");
  var current = window.location.pathname + window.location.search;

  links.forEach(function (a) {
    var href = a.getAttribute("href");
    if (href && current.indexOf(href) !== -1) {
      a.classList.add("active");
    }
  });

  // Simple confirm buttons (add data-confirm="Are you sure?")
  document.addEventListener("click", function (e) {
    var t = e.target;
    if (t && t.dataset && t.dataset.confirm) {
      var ok = confirm(t.dataset.confirm);
      if (!ok) e.preventDefault();
    }
  });
});
