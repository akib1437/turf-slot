<?php

require_once __DIR__ . "/dbConnect.php";

function createSlot($slotDate, $startTime, $endTime) {
    $conn = dbConnect();

    // Check duplicate 
    $checkSql = "SELECT id FROM slots WHERE slot_date = ? AND start_time = ? AND end_time = ?";
    $check = $conn->prepare($checkSql);
    $check->bind_param("sss", $slotDate, $startTime, $endTime);
    $check->execute();
    $res = $check->get_result();
    if ($res->num_rows > 0) {
        $check->close();
        $conn->close();
        return ["ok" => false, "error" => "This slot already exists for the selected date."];
    }
    $check->close();

    $sql = "INSERT INTO slots (slot_date, start_time, end_time) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $slotDate, $startTime, $endTime);
    $ok = $stmt->execute();

    $stmt->close();
    $conn->close();

    if ($ok) return ["ok" => true];
    return ["ok" => false, "error" => "Failed to add slot."];
}

function getSlotsByDate($slotDate) {
    $conn = dbConnect();

    $sql = "SELECT id, slot_date, start_time, end_time
            FROM slots
            WHERE slot_date = ?
            ORDER BY start_time ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $slotDate);
    $stmt->execute();

    $result = $stmt->get_result();
    $slots = [];
    while ($row = $result->fetch_assoc()) {
        $slots[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $slots;
}

function getSlotById($slotId) {
    $conn = dbConnect();

    $sql = "SELECT id, slot_date, start_time, end_time FROM slots WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $slotId);
    $stmt->execute();

    $result = $stmt->get_result();
    $slot = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    return $slot ? $slot : null;
}

function updateSlot($slotId, $slotDate, $startTime, $endTime) {
    $conn = dbConnect();

    // Check duplicate 
    $checkSql = "SELECT id FROM slots
                 WHERE slot_date = ? AND start_time = ? AND end_time = ? AND id != ?";
    $check = $conn->prepare($checkSql);
    $check->bind_param("sssi", $slotDate, $startTime, $endTime, $slotId);
    $check->execute();
    $res = $check->get_result();
    if ($res->num_rows > 0) {
        $check->close();
        $conn->close();
        return ["ok" => false, "error" => "Another slot already exists with same time."];
    }
    $check->close();

    $sql = "UPDATE slots SET slot_date = ?, start_time = ?, end_time = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $slotDate, $startTime, $endTime, $slotId);
    $ok = $stmt->execute();

    $stmt->close();
    $conn->close();

    if ($ok) return ["ok" => true];
    return ["ok" => false, "error" => "Failed to update slot."];
}

function deleteSlot($slotId) {
    $conn = dbConnect();

    $sql = "DELETE FROM slots WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $slotId);
    $ok = $stmt->execute();

    $stmt->close();
    $conn->close();

    return $ok;
}
