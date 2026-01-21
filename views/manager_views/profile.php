<?php
session_start();
require_once __DIR__ . "/../../models/userModel.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../common_views/login.php?err=Please login first");
    exit();
}
if ($_SESSION["role"] !== "manager") {
    header("Location: ../common_views/login.php?err=Access denied");
    exit();
}

$user = getUserById((int)$_SESSION["user_id"]);
if (!$user) {
    header("Location: home.php?err=User not found");
    exit();
}

$msg = $_GET["msg"] ?? "";
$err = $_GET["err"] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TurfSlot - Manager Profile</title>
  <link rel="stylesheet" href="../common_views/css/styles.css">
  <link rel="stylesheet" href="css/styles.css">
  <script src="js/script.js" defer></script>
</head>
<body>
  <div class="page">
    <div class="topbar">
      <div class="brand">TurfSlot (Manager)</div>
      <div class="top-actions">
        <a class="link" href="home.php">Home</a>
        <a class="link" href="../../controllers/authControl.php?action=logout">Logout</a>
      </div>
    </div>

    <div class="content">
      <h2>My Profile</h2>

      <?php if ($msg === "updated"): ?>
        <div class="alert success">Profile updated successfully.</div>
      <?php endif; ?>

      <?php if ($err): ?>
        <div class="alert error"><?php echo htmlspecialchars($err); ?></div>
      <?php endif; ?>

      <div class="card">
        <form action="../../controllers/profileControl.php" method="POST" id="profileForm" novalidate>
          <input type="hidden" name="action" value="update_profile">

          <label>Name</label>
          <input type="text" name="name" id="pName" value="<?php echo htmlspecialchars($user["name"]); ?>" required>

          <label>Email (cannot change)</label>
          <input type="email" value="<?php echo htmlspecialchars($user["email"]); ?>" disabled>

          <label>Phone</label>
          <input type="text" name="phone" id="pPhone" value="<?php echo htmlspecialchars($user["phone"]); ?>" required>

          <label>New Password (optional)</label>
          <input type="password" name="new_password" id="pPass" placeholder="Leave empty to keep old password">

          <button type="submit" class="btn">Update Profile</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
