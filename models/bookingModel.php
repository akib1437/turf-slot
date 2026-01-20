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
// ===== Day 5 additions =====

function getBookingsByUser($userId) {
    $conn = dbConnect();

    $sql = "SELECT 
                b.id AS booking_id,
                b.team_name,
                b.phone,
                b.status,
                b.created_at,
                s.slot_date,
                s.start_time,
                s.end_time
            FROM bookings b
            INNER JOIN slots s ON b.slot_id = s.id
            WHERE b.user_id = ?
            ORDER BY s.slot_date DESC, s.start_time DESC, b.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);   // IMPORTANT: no backslashes here
    $stmt->execute();

    $result = $stmt->get_result();
    $bookings = [];

    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $bookings;
}

function cancelPendingBooking($bookingId, $userId) {
    $conn = dbConnect();

    // Ensure booking belongs to user AND is Pending
    $checkSql = "SELECT id FROM bookings
                 WHERE id = ? AND user_id = ? AND status = 'Pending'
                 LIMIT 1";
    $check = $conn->prepare($checkSql);
    $check->bind_param("ii", $bookingId, $userId);
    $check->execute();

    $res = $check->get_result();
    if ($res->num_rows === 0) {
        $check->close();
        $conn->close();
        return ["ok" => false, "error" => "Only Pending bookings can be cancelled."];
    }
    $check->close();

    // Cancel booking
    $sql = "UPDATE bookings SET status = 'Cancelled' WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $bookingId, $userId);
    $ok = $stmt->execute();

    $stmt->close();
    $conn->close();

    if ($ok) return ["ok" => true];
    return ["ok" => false, "error" => "Cancel failed. Try again."];
}
// ===== Day 6 additions (Manager features) =====

function getPendingBookingRequests() {
    $conn = dbConnect();

    $sql = "SELECT
                b.id AS booking_id,
                b.user_id,
                b.slot_id,
                b.team_name,
                b.phone,
                b.status,
                b.created_at,
                u.name AS customer_name,
                u.email AS customer_email,
                s.slot_date,
                s.start_time,
                s.end_time
            FROM bookings b
            INNER JOIN users u ON b.user_id = u.id
            INNER JOIN slots s ON b.slot_id = s.id
            WHERE b.status = 'Pending'
            ORDER BY s.slot_date ASC, s.start_time ASC, b.created_at ASC";

    $result = $conn->query($sql);
    $rows = [];

    if ($result) {
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
    }

    $conn->close();
    return $rows;
}

function getBookingById($bookingId) {
    $conn = dbConnect();

    $sql = "SELECT id, slot_id, status FROM bookings WHERE id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();

    $res = $stmt->get_result();
    $row = $res->fetch_assoc();

    $stmt->close();
    $conn->close();

    return $row ? $row : null;
}

function isSlotAlreadyApproved($slotId) {
    $conn = dbConnect();

    $sql = "SELECT id FROM bookings WHERE slot_id = ? AND status = 'Approved' LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $slotId);
    $stmt->execute();

    $res = $stmt->get_result();
    $exists = $res->num_rows > 0;

    $stmt->close();
    $conn->close();

    return $exists;
}

function approveBooking($bookingId) {
    $booking = getBookingById($bookingId);
    if (!$booking) return ["ok" => false, "error" => "Booking not found."];

    if ($booking["status"] !== "Pending") {
        return ["ok" => false, "error" => "Only Pending bookings can be approved."];
    }

    $slotId = (int)$booking["slot_id"];
    if (isSlotAlreadyApproved($slotId)) {
        return ["ok" => false, "error" => "This slot already has an Approved booking."];
    }

    $conn = dbConnect();
    $sql = "UPDATE bookings SET status = 'Approved' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookingId);
    $ok = $stmt->execute();

    $stmt->close();
    $conn->close();

    if ($ok) return ["ok" => true];
    return ["ok" => false, "error" => "Approve failed."];
}

function rejectBooking($bookingId) {
    $booking = getBookingById($bookingId);
    if (!$booking) return ["ok" => false, "error" => "Booking not found."];

    if ($booking["status"] !== "Pending") {
        return ["ok" => false, "error" => "Only Pending bookings can be rejected."];
    }

    $conn = dbConnect();
    $sql = "UPDATE bookings SET status = 'Rejected' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookingId);
    $ok = $stmt->execute();

    $stmt->close();
    $conn->close();

    if ($ok) return ["ok" => true];
    return ["ok" => false, "error" => "Reject failed."];
}

function getApprovedBookingsByDate($date) {
    $conn = dbConnect();

    $sql = "SELECT
                b.id AS booking_id,
                b.team_name,
                b.phone,
                u.name AS customer_name,
                u.email AS customer_email,
                s.slot_date,
                s.start_time,
                s.end_time
            FROM bookings b
            INNER JOIN users u ON b.user_id = u.id
            INNER JOIN slots s ON b.slot_id = s.id
            WHERE b.status = 'Approved' AND s.slot_date = ?
            ORDER BY s.start_time ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();

    $res = $stmt->get_result();
    $rows = [];
    while ($r = $res->fetch_assoc()) {
        $rows[] = $r;
    }

    $stmt->close();
    $conn->close();

    return $rows;
}
