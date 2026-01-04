<?php
$pageTitle = "Register";
$pageCss = ["auth-register.css"];
$pageJs  = ["auth-register.js"];
include __DIR__ . "/../layouts/header.php";
?>

<main class="container">
  <div class="card auth-card">
    <h1 class="page-title">Register</h1>

    <p class="muted">Create an account as Customer (manager will be created manually later).</p>

    <div id="regMsg" class="msg msg-hidden"></div>

    <form id="registerForm" method="post" action="/index.php?c=auth&a=register">
      <div class="form-row">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Your name" />
      </div>

      <div class="form-row">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="example@mail.com" />
      </div>

      <div class="form-row">
        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" placeholder="01XXXXXXXXX" />
      </div>

      <div class="form-row">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Create password" />
      </div>

      <div class="form-row">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" />
      </div>

      <!-- Keep it simple for UI. Later backend can force customer role -->
      <input type="hidden" name="role" value="customer" />

      <button class="btn btn-primary" type="submit">Create Account</button>

      <p class="small">
        Already have an account?
        <a href="/index.php?c=auth&a=login">Login</a>
      </p>
    </form>
  </div>
</main>

<?php include __DIR__ . "/../layouts/footer.php"; ?>
