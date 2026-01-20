<?php
// models/bookingModel.php

require_once __DIR__ . "/dbConnect.php";

/*
  A slot is considered NOT available if there is an APPROVED booking for that slot_id.
*/

function isSlotAvailable($slotId) {
    $conn = dbConnect();

    $sql = "SELECT id FROM bookings WHERE slot_id = ? AND status = 'Approved' LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $slotId);
    $stmt->execute();

    $result = $stmt->get_result();
    $found = $result->num_rows > 0;

    $stmt->close();
    $conn->close();

    return !$found; // available if no approved booking exists
}

function createBookingRequest($userId, $slotId, $teamName, $phone) {
    $conn = dbConnect();

    // Basic check: slot must exist
    $checkSlotSql = "SELECT id FROM slots WHERE id = ? LIMIT 1";
    $check = $conn->prepare($checkSlotSql);
    $check->bind_param("i", $slotId);
    $check->execute();
    $slotRes = $check->get_result();
    if ($slotRes->num_rows === 0) {
        $check->close();
        $conn->close();
        return ["ok" => false, "error" => "Slot not found."];
    }
    $check->close();

    // Check if already approved for that slot
    $checkApprovedSql = "SELECT id FROM bookings WHERE slot_id = ? AND status = 'Approved' LIMIT 1";
    $chk2 = $conn->prepare($checkApprovedSql);
    $chk2->bind_param("i", $slotId);
    $chk2->execute();
    $res2 = $chk2->get_result();
    if ($res2->num_rows > 0) {
        $chk2->close();
        $conn->close();
        return ["ok" => false, "error" => "This slot is already booked."];
    }
    $chk2->close();

    // Optional: prevent duplicate pending from same user for same slot
    $dupSql = "SELECT id FROM bookings WHERE user_id = ? AND slot_id = ? AND status = 'Pending' LIMIT 1";
    $dup = $conn->prepare($dupSql);
    $dup->bind_param("ii", $userId, $slotId);
    $dup->execute();
    $dupRes = $dup->get_result();
    if ($dupRes->num_rows > 0) {
        $dup->close();
        $conn->close();
        return ["ok" => false, "error" => "You already requested this slot (Pending)." ];
    }
    $dup->close();

    $sql = "INSERT INTO bookings (user_id, slot_id, team_name, phone, status)
            VALUES (?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $userId, $slotId, $teamName, $phone);
    $ok = $stmt->execute();

    $stmt->close();
    $conn->close();

    if ($ok) return ["ok" => true];
    return ["ok" => false, "error" => "Failed to request booking."];
}
