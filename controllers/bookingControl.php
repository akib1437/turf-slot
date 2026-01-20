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

// Must be logged in
if (!isset($_SESSION["user_id"])) {
    redirectTo("../views/common_views/login.php?err=Please login first");
}

// For Day 4: only customer can request booking
if ($_SESSION["role"] !== "customer") {
    redirectTo("../views/common_views/login.php?err=Access denied");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirectTo("../views/customer_views/home.php?err=Invalid request");
}

$action = $_POST["action"] ?? "";

if ($action !== "request_booking") {
    redirectTo("../views/customer_views/home.php?err=Invalid action");
}

$userId = (int)$_SESSION["user_id"];
$slotId = (int)($_POST["slot_id"] ?? 0);
$teamName = clean($_POST["team_name"] ?? "");
$phone = clean($_POST["phone"] ?? "");

if ($slotId <= 0 || $teamName === "" || $phone === "") {
    redirectTo("../views/customer_views/requestBooking.php?slot_id=" . $slotId . "&err=Please fill all fields");
}

$res = createBookingRequest($userId, $slotId, $teamName, $phone);

if ($res["ok"]) {
    // Day 5 will build myBookings.php, but for now redirect to home with msg
    redirectTo("../views/customer_views/home.php?msg=request_sent");
} else {
    redirectTo("../views/customer_views/requestBooking.php?slot_id=" . $slotId . "&err=" . urlencode($res["error"]));
}
