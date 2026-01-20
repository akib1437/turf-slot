<?php
// views/customer_views/requestBooking.php
session_start();

require_once __DIR__ . "/../../models/slotModel.php";
require_once __DIR__ . "/../../models/bookingModel.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../common_views/login.php?err=Please login first");
    exit();
}
if ($_SESSION["role"] !== "customer") {
    header("Location: ../common_views/login.php?err=Access denied");
    exit();
}

$slotId = isset($_GET["slot_id"]) ? (int)$_GET["slot_id"] : 0;
$err = $_GET["err"] ?? "";

if ($slotId <= 0) {
    header("Location: viewSlots.php?err=Invalid slot");
    exit();
}

$slot = getSlotById($slotId);
if (!$slot) {
    header("Location: viewSlots.php?err=Slot not found");
    exit();
}

$available = isSlotAvailable($slotId);

// Prefill phone from session user profile if you want later; for now blank
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TurfSlot - Request Booking</title>
  <link rel="stylesheet" href="css/styles.css">
  <script src="js/script.js" defer></script>
</head>
<body>
  <div class="page">
    <div class="topbar">
      <div class="brand">TurfSlot (Customer)</div>
      <div class="top-actions">
        <a class="link" href="home.php">Home</a>
        <a class="link" href="viewSlots.php?date=<?php echo htmlspecialchars($slot["slot_date"]); ?>">Back</a>
        <a class="link" href="../../controllers/authControl.php?action=logout">Logout</a>
      </div>
    </div>

    <div class="content">
      <h2>Request Booking</h2>

      <?php if ($err): ?>
        <div class="alert error"><?php echo htmlspecialchars($err); ?></div>
      <?php endif; ?>

      <div class="card">
        <p><b>Date:</b> <?php echo htmlspecialchars($slot["slot_date"]); ?></p>
        <p><b>Time:</b> <?php echo htmlspecialchars(substr($slot["start_time"],0,5)); ?>
           - <?php echo htmlspecialchars(substr($slot["end_time"],0,5)); ?></p>

        <?php if (!$available): ?>
          <div class="alert error">This slot is already booked (Approved). Please choose another slot.</div>
          <a class="btn" href="viewSlots.php?date=<?php echo htmlspecialchars($slot["slot_date"]); ?>">Go Back</a>
        <?php else: ?>
          <form id="bookingForm" action="../../controllers/bookingControl.php" method="POST" novalidate>
            <input type="hidden" name="action" value="request_booking">
            <input type="hidden" name="slot_id" value="<?php echo (int)$slotId; ?>">

            <label>Team Name</label>
            <input type="text" name="team_name" id="teamName" placeholder="Your team name" required>

            <label>Phone</label>
            <input type="text" name="phone" id="phone" placeholder="01XXXXXXXXX" required>

            <button type="submit" class="btn">Submit Request (Pending)</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
