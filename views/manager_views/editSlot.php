<?php
// views/manager_views/editSlot.php
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

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$err = $_GET["err"] ?? "";

if ($id <= 0) {
    header("Location: manageSlots.php?err=Invalid slot");
    exit();
}

$slot = getSlotById($id);
if (!$slot) {
    header("Location: manageSlots.php?err=Slot not found");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TurfSlot - Edit Slot</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
</head>

<body>
    <div class="page">
        <div class="topbar">
            <div class="brand">TurfSlot (Manager)</div>
            <div class="top-actions">
                <a class="link" href="manageSlots.php?date=<?php echo htmlspecialchars($slot["slot_date"]); ?>">Back</a>
                <a class="link" href="../../controllers/authControl.php?action=logout">Logout</a>
            </div>
        </div>

        <div class="content">
            <h2>Edit Slot</h2>

            <?php if ($err): ?>
                <div class="alert error"><?php echo htmlspecialchars($err); ?></div>
            <?php endif; ?>

            <div class="card">
                <form id="editSlotForm" action="../../controllers/managerControl.php" method="POST" novalidate>
                    <input type="hidden" name="action" value="update_slot">
                    <input type="hidden" name="slot_id" value="<?php echo (int) $slot["id"]; ?>">

                    <label>Date</label>
                    <input type="date" name="slot_date" id="slotDate"
                        value="<?php echo htmlspecialchars($slot["slot_date"]); ?>" required>

                    <label>Start Time</label>
                    <input type="time" name="start_time" id="startTime"
                        value="<?php echo htmlspecialchars(substr($slot["start_time"], 0, 5)); ?>" required>

                    <label>End Time</label>
                    <input type="time" name="end_time" id="endTime"
                        value="<?php echo htmlspecialchars(substr($slot["end_time"], 0, 5)); ?>" required>

                    <button type="submit" class="btn">Update Slot</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>