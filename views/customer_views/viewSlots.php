<?php
// views/customer_views/viewSlots.php
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

$date = $_GET["date"] ?? date("Y-m-d");
$err = $_GET["err"] ?? "";
$msg = $_GET["msg"] ?? "";

$slots = [];
if ($date) {
    $slots = getSlotsByDate($date);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TurfSlot - View Slots</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
</head>

<body>
    <div class="page">
        <div class="topbar">
            <div class="brand">TurfSlot (Customer)</div>
            <div class="top-actions">
                <a class="link" href="home.php">Home</a>
                <a class="link" href="profile.php">Profile</a>
                <a class="link" href="../../controllers/authControl.php?action=logout">Logout</a>
            </div>
        </div>

        <div class="content">
            <h2>Available Slots</h2>

            <?php if ($msg): ?>
                <div class="alert success"><?php echo htmlspecialchars($msg); ?></div>
            <?php endif; ?>

            <?php if ($err): ?>
                <div class="alert error"><?php echo htmlspecialchars($err); ?></div>
            <?php endif; ?>

            <div class="card wide">
                <form action="../../controllers/customerControl.php" method="POST" class="row-form">
                    <input type="hidden" name="action" value="view_slots_by_date">
                    <div class="row">
                        <div class="col">
                            <label>Select Date</label>
                            <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required>
                        </div>
                        <div class="col btn-col">
                            <button type="submit" class="btn">Load Slots</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card wide">
                <h3>Slots for: <?php echo htmlspecialchars($date); ?></h3>

                <?php if (count($slots) === 0): ?>
                    <p class="muted">No slots created for this date yet.</p>
                <?php else: ?>
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($slots as $i => $s): ?>
                                    <?php
                                    $slotId = (int) $s["id"];
                                    $available = isSlotAvailable($slotId);
                                    ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td>
                                            <?php echo htmlspecialchars(substr($s["start_time"], 0, 5)); ?>
                                            - <?php echo htmlspecialchars(substr($s["end_time"], 0, 5)); ?>
                                        </td>
                                        <td>
                                            <?php if ($available): ?>
                                                <span class="badge ok">Available</span>
                                            <?php else: ?>
                                                <span class="badge bad">Booked</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($available): ?>
                                                <a class="btn-small" href="requestBooking.php?slot_id=<?php echo $slotId; ?>">
                                                    Request Booking
                                                </a>
                                            <?php else: ?>
                                                <span class="muted">Not available</span>
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