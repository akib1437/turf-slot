<?php
// Basic defaults so pages don't break if you forget to set them
if (!isset($pageTitle)) { $pageTitle = "Turf Slot System"; }
if (!isset($pageCss)) { $pageCss = []; } // example: ["auth-login.css"]
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($pageTitle); ?></title>

  <!-- Common CSS -->
  <link rel="stylesheet" href="/assets/css/common.css" />

  <!-- Page CSS -->
  <?php foreach ($pageCss as $cssFile): ?>
    <link rel="stylesheet" href="/assets/css/<?php echo htmlspecialchars($cssFile); ?>" />
  <?php endforeach; ?>
</head>
<body>
  <header class="topbar">
    <div class="topbar-inner">
      <div class="brand">
        <a href="/index.php" class="brand-link">Turf Slot System</a>
      </div>

      <nav class="nav">
        <!-- These links are simple now; later you can show/hide based on session role -->
        <a class="nav-link" href="/index.php?c=auth&a=login">Login</a>
        <a class="nav-link" href="/index.php?c=auth&a=register">Register</a>

        <span class="nav-sep">|</span>

        <a class="nav-link" href="/index.php?c=customer&a=slots">Customer</a>
        <a class="nav-link" href="/index.php?c=manager&a=slotsManage">Manager</a>
      </nav>
    </div>
  </header>

  <div class="page">
