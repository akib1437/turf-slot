<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../common_views/login.php?err=Please login first");
    exit();
}
if ($_SESSION["role"] !== "customer") {
    header("Location: ../common_views/login.php?err=Access denied");
    exit();
}

$name = $_SESSION["name"] ?? "Customer";
$err = $_GET["err"] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TurfSlot - Customer Home</title>
  <link rel="stylesheet" href="../common_views/css/styles.css">
  <link rel="stylesheet" href="css/styles.css">
  <script src="js/script.js" defer></script>
</head>
<body>
  <div class="page">
    <div class="topbar">
      <div class="brand">TurfSlot</div>
      <div class="top-actions">
        <a class="link" href="profile.php">Profile</a>
        <a class="link" href="../../controllers/authControl.php?action=logout">Logout</a>
      </div>
    </div>

    <div class="content">
      <h2>Welcome, <?php echo htmlspecialchars($name); ?></h2>

      <?php if ($err): ?>
        <div class="alert error"><?php echo htmlspecialchars($err); ?></div>
      <?php endif; ?>

      <div class="grid">
        <a class="menu-card" href="viewSlots.php">
          <h3>View Available Slots</h3>
          <p>Select date and see slots.</p>
        </a>

        <a class="menu-card" href="myBookings.php">
          <h3>My Bookings</h3>
          <p>See Pending / Approved / Rejected.</p>
        </a>

        <a class="menu-card" href="profile.php">
          <h3>Profile Update</h3>
          <p>Update name, phone, password.</p>
        </a>
      </div>
    </div>
  </div>
</body>
</html>
