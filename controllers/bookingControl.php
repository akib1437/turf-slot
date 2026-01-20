<?php
// controllers/bookingControl.php

session_start();
require_once __DIR__ . "/../models/bookingModel.php";

function redirectTo($path) {
    header("Location: " . $path);
    exit();
}

function clean($v) {
    return trim($v);
}

if (!isset($_SESSION["user_id"])) {
    redirectTo("../views/common_views/login.php?err=Please login first");
}

if ($_SESSION["role"] !== "customer") {
    redirectTo("../views/common_views/login.php?err=Access denied");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirectTo("../views/customer_views/home.php?err=Invalid request");
}

$action = $_POST["action"] ?? "";
$userId = (int)$_SESSION["user_id"];

/* ===== Request Booking (Day 4) ===== */
if ($action === "request_booking") {
    $slotId = (int)($_POST["slot_id"] ?? 0);
    $teamName = clean($_POST["team_name"] ?? "");
    $phone = clean($_POST["phone"] ?? "");

    if ($slotId <= 0 || $teamName === "" || $phone === "") {
        redirectTo("../views/customer_views/requestBooking.php?slot_id=" . $slotId . "&err=Please fill all fields");
    }

    $res = createBookingRequest($userId, $slotId, $teamName, $phone);

    if ($res["ok"]) {
        redirectTo("../views/customer_views/myBookings.php?msg=request_sent");
    } else {
        redirectTo("../views/customer_views/requestBooking.php?slot_id=" . $slotId . "&err=" . urlencode($res["error"]));
    }
}

/* ===== Cancel Pending (Day 5) ===== */
if ($action === "cancel_booking") {
    $bookingId = (int)($_POST["booking_id"] ?? 0);
    if ($bookingId <= 0) {
        redirectTo("../views/customer_views/myBookings.php?err=Invalid booking");
    }

    $res = cancelPendingBooking($bookingId, $userId);
    if ($res["ok"]) {
        redirectTo("../views/customer_views/myBookings.php?msg=cancelled");
    } else {
        redirectTo("../views/customer_views/myBookings.php?err=" . urlencode($res["error"]));
    }
}

redirectTo("../views/customer_views/home.php?err=Invalid action");
