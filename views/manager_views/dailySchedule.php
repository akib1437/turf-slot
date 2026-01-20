<?php
// views/manager_views/dailySchedule.php
session_start();
require_once __DIR__ . "/../../models/bookingModel.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../common_views/login.php?err=Please login first");
    exit();
}
if ($_SESSION["role"] !== "manager") {
    header("Location: ../common_views/login.php?err=Access denied");
    exit();
}

$date = $_GET["date"] ?? date("Y-m-d");
$err = $_GET["err"] ?? "";
$msg = $_GET["msg"] ?? "";

$approved = [];
if ($date) {
    $approved = getApprovedBookingsByDate($date);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TurfSlot - Daily Schedule</title>
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
        <a class="link" href="manageSlots.php">Manage Slots</a>
        <a class="link" href="bookingRequests.php">Requests</a>
        <a class="link" href="../../controllers/authControl.php?action=logout">Logout</a>
      </div>
    </div>

    <div class="content">
      <h2>Daily Schedule (Approved Bookings)</h2>

      <?php if ($msg): ?>
        <div class="alert success"><?php echo htmlspecialchars($msg); ?></div>
      <?php endif; ?>

      <?php if ($err): ?>
        <div class="alert error"><?php echo htmlspecialchars($err); ?></div>
      <?php endif; ?>

      <div class="card wide">
        <form action="dailySchedule.php" method="GET" class="row-form">
          <div class="row">
            <div class="col">
              <label>Select Date</label>
              <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required>
            </div>
            <div class="col btn-col">
              <button type="submit" class="btn">Load Schedule</button>
            </div>
          </div>
        </form>
      </div>

      <div class="card wide">
        <h3>Date: <?php echo htmlspecialchars($date); ?></h3>

        <?php if (count($approved) === 0): ?>
          <p class="muted">No approved bookings found for this date.</p>
        <?php else: ?>
          <div class="table-wrap">
            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Time</th>
                  <th>Customer</th>
                  <th>Email</th>
                  <th>Team</th>
                  <th>Phone</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($approved as $i => $a): ?>
                  <tr>
                    <td><?php echo $i + 1; ?></td>
                    <td>
                      <?php echo htmlspecialchars(substr($a["start_time"],0,5)); ?>
                      -
                      <?php echo htmlspecialchars(substr($a["end_time"],0,5)); ?>
                    </td>
                    <td><?php echo htmlspecialchars($a["customer_name"]); ?></td>
                    <td><?php echo htmlspecialchars($a["customer_email"]); ?></td>
                    <td><?php echo htmlspecialchars($a["team_name"]); ?></td>
                    <td><?php echo htmlspecialchars($a["phone"]); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</body>
</html>
