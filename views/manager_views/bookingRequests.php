<?php
// views/manager_views/bookingRequests.php
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

$msg = $_GET["msg"] ?? "";
$err = $_GET["err"] ?? "";

$requests = getPendingBookingRequests();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TurfSlot - Booking Requests</title>
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
                <a class="link" href="dailySchedule.php">Daily Schedule</a>
                <a class="link" href="../../controllers/authControl.php?action=logout">Logout</a>
            </div>
        </div>

        <div class="content">
            <h2>Booking Requests (Pending)</h2>

            <?php if ($msg): ?>
                <div class="alert success"><?php echo htmlspecialchars($msg); ?></div>
            <?php endif; ?>

            <?php if ($err): ?>
                <div class="alert error"><?php echo htmlspecialchars($err); ?></div>
            <?php endif; ?>

            <div class="card wide">
                <?php if (count($requests) === 0): ?>
                    <p class="muted">No pending requests.</p>
                <?php else: ?>
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Team</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($requests as $i => $r): ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo htmlspecialchars($r["slot_date"]); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars(substr($r["start_time"], 0, 5)); ?>
                                            -
                                            <?php echo htmlspecialchars(substr($r["end_time"], 0, 5)); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($r["customer_name"]); ?></td>
                                        <td><?php echo htmlspecialchars($r["customer_email"]); ?></td>
                                        <td><?php echo htmlspecialchars($r["team_name"]); ?></td>
                                        <td><?php echo htmlspecialchars($r["phone"]); ?></td>
                                        <td class="actions">
                                            <form class="inline" action="../../controllers/bookingControl.php" method="POST"
                                                onsubmit="return confirmApprove();">
                                                <input type="hidden" name="action" value="approve_booking">
                                                <input type="hidden" name="booking_id"
                                                    value="<?php echo (int) $r["booking_id"]; ?>">
                                                <button type="submit" class="btn-small">Approve</button>
                                            </form>

                                            <form class="inline" action="../../controllers/bookingControl.php" method="POST"
                                                onsubmit="return confirmReject();">
                                                <input type="hidden" name="action" value="reject_booking">
                                                <input type="hidden" name="booking_id"
                                                    value="<?php echo (int) $r["booking_id"]; ?>">
                                                <button type="submit" class="btn-small danger">Reject</button>
                                            </form>
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