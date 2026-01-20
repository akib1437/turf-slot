<?php
// views/customer_views/myBookings.php
session_start();

require_once __DIR__ . "/../../models/bookingModel.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../common_views/login.php?err=Please login first");
    exit();
}
if ($_SESSION["role"] !== "customer") {
    header("Location: ../common_views/login.php?err=Access denied");
    exit();
}

$userId = (int) $_SESSION["user_id"];
$bookings = getBookingsByUser($userId);

$msg = $_GET["msg"] ?? "";
$err = $_GET["err"] ?? "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TurfSlot - My Bookings</title>
    <link rel="stylesheet" href="../common_views/css/styles.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
</head>

<body>
    <div class="page">
        <div class="topbar">
            <div class="brand">TurfSlot (Customer)</div>
            <div class="top-actions">
                <a class="link" href="home.php">Home</a>
                <a class="link" href="viewSlots.php">View Slots</a>
                <a class="link" href="../../controllers/authControl.php?action=logout">Logout</a>
            </div>
        </div>

        <div class="content">
            <h2>My Bookings</h2>

            <?php if ($msg === "request_sent"): ?>
                <div class="alert success">Booking request sent. Status is Pending.</div>
            <?php elseif ($msg === "cancelled"): ?>
                <div class="alert success">Booking cancelled successfully.</div>
            <?php endif; ?>

            <?php if ($err): ?>
                <div class="alert error"><?php echo htmlspecialchars($err); ?></div>
            <?php endif; ?>

            <div class="card wide">
                <?php if (count($bookings) === 0): ?>
                    <p class="muted">No bookings found.</p>
                <?php else: ?>
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Team</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $i => $b): ?>
                                    <?php
                                    $status = $b["status"];
                                    $badgeClass = "badge";
                                    if ($status === "Approved")
                                        $badgeClass .= " ok";
                                    else if ($status === "Pending")
                                        $badgeClass .= " warn";
                                    else if ($status === "Rejected")
                                        $badgeClass .= " bad";
                                    else if ($status === "Cancelled")
                                        $badgeClass .= " gray";
                                    ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo htmlspecialchars($b["slot_date"]); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars(substr($b["start_time"], 0, 5)); ?>
                                            -
                                            <?php echo htmlspecialchars(substr($b["end_time"], 0, 5)); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($b["team_name"]); ?></td>
                                        <td><?php echo htmlspecialchars($b["phone"]); ?></td>
                                        <td><span
                                                class="<?php echo $badgeClass; ?>"><?php echo htmlspecialchars($status); ?></span>
                                        </td>
                                        <td>
                                            <?php if ($status === "Pending"): ?>
                                                <form class="inline" action="../../controllers/bookingControl.php" method="POST"
                                                    onsubmit="return confirmCancel();">
                                                    <input type="hidden" name="action" value="cancel_booking">
                                                    <input type="hidden" name="booking_id"
                                                        value="<?php echo (int) $b["booking_id"]; ?>">
                                                    <button type="submit" class="btn-small danger">Cancel</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="muted">—</span>
                                            <?php endif; ?>
                                        </td>
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