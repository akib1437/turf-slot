<?php
session_start();
require_once __DIR__ . "/../../models/slotModel.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../common_views/login.php?err=Please login first");
    exit();
}
if ($_SESSION["role"] !== "manager") {
    header("Location: ../common_views/login.php?err=Access denied");
    exit();
}

$date = $_GET["date"] ?? date("Y-m-d");
$msg = $_GET["msg"] ?? "";
$err = $_GET["err"] ?? "";

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
    <title>TurfSlot - Manage Slots</title>
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
                <a class="link" href="profile.php">Profile</a>
                <a class="link" href="../../controllers/authControl.php?action=logout">Logout</a>
            </div>
        </div>

        <div class="content">
            <h2>Manage Time Slots</h2>

            <?php if ($msg): ?>
                <div class="alert success">
                    <?php echo htmlspecialchars(str_replace("_", " ", $msg)); ?>
                </div>
            <?php endif; ?>

            <?php if ($err): ?>
                <div class="alert error"><?php echo htmlspecialchars($err); ?></div>
            <?php endif; ?>

            <div class="card wide">
                <form class="row-form" action="manageSlots.php" method="GET">
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
                <h3>Add New Slot</h3>
                <form id="addSlotForm" action="../../controllers/managerControl.php" method="POST" novalidate>
                    <input type="hidden" name="action" value="add_slot">
                    <input type="hidden" name="slot_date" value="<?php echo htmlspecialchars($date); ?>">

                    <div class="row">
                        <div class="col">
                            <label>Start Time</label>
                            <input type="time" name="start_time" id="startTime" required>
                        </div>
                        <div class="col">
                            <label>End Time</label>
                            <input type="time" name="end_time" id="endTime" required>
                        </div>
                        <div class="col btn-col">
                            <button type="submit" class="btn">Add Slot</button>
                        </div>
                    </div>
                </form>
                <p class="hint">Tip: End time must be after start time.</p>
            </div>

            <div class="card wide">
                <h3>Slots for: <?php echo htmlspecialchars($date); ?></h3>

                <?php if (count($slots) === 0): ?>
                    <p class="muted">No slots found for this date.</p>
                <?php else: ?>
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($slots as $i => $s): ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo htmlspecialchars($s["slot_date"]); ?></td>
                                        <td><?php echo htmlspecialchars(substr($s["start_time"], 0, 5)); ?></td>
                                        <td><?php echo htmlspecialchars(substr($s["end_time"], 0, 5)); ?></td>
                                        <td class="actions">
                                            <a class="btn-small" href="editSlot.php?id=<?php echo (int) $s["id"]; ?>">Edit</a>

                                            <form class="inline" action="../../controllers/managerControl.php" method="POST"
                                                onsubmit="return confirmDelete();">
                                                <input type="hidden" name="action" value="delete_slot">
                                                <input type="hidden" name="slot_id" value="<?php echo (int) $s["id"]; ?>">
                                                <input type="hidden" name="slot_date"
                                                    value="<?php echo htmlspecialchars($date); ?>">
                                                <button type="submit" class="btn-small danger">Delete</button>
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