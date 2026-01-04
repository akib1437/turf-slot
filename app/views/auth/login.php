<?php
$pageTitle = "Login";
$pageCss = ["auth-login.css"];
$pageJs  = ["auth-login.js"];
include __DIR__ . "/../layouts/header.php";
?>

<main class="container">
  <div class="card auth-card">
    <h1 class="page-title">Login</h1>

    <p class="muted">Use your email and password to login.</p>

    <div id="loginMsg" class="msg msg-hidden"></div>

    <form id="loginForm" method="post" action="/index.php?c=auth&a=login">
      <div class="form-row">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="example@mail.com" />
      </div>

      <div class="form-row">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Your password" />
      </div>

      <button class="btn btn-primary" type="submit">Login</button>

      <p class="small">
        Don’t have an account?
        <a href="/index.php?c=auth&a=register">Register</a>
      </p>
    </form>
  </div>
</main>

<?php include __DIR__ . "/../layouts/footer.php"; ?>
