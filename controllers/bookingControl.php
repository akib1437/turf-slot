<?php

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

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirectTo("../views/common_views/login.php?err=Invalid request");
}

$action = $_POST["action"] ?? "";


if ($_SESSION["role"] === "customer") {

    $userId = (int)$_SESSION["user_id"];

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
}


if ($_SESSION["role"] === "manager") {

    if ($action === "approve_booking") {
        $bookingId = (int)($_POST["booking_id"] ?? 0);
        if ($bookingId <= 0) {
            redirectTo("../views/manager_views/bookingRequests.php?err=Invalid booking");
        }

        $res = approveBooking($bookingId);
        if ($res["ok"]) {
            redirectTo("../views/manager_views/bookingRequests.php?msg=Booking approved");
        } else {
            redirectTo("../views/manager_views/bookingRequests.php?err=" . urlencode($res["error"]));
        }
    }

    if ($action === "reject_booking") {
        $bookingId = (int)($_POST["booking_id"] ?? 0);
        if ($bookingId <= 0) {
            redirectTo("../views/manager_views/bookingRequests.php?err=Invalid booking");
        }

        $res = rejectBooking($bookingId);
        if ($res["ok"]) {
            redirectTo("../views/manager_views/bookingRequests.php?msg=Booking rejected");
        } else {
            redirectTo("../views/manager_views/bookingRequests.php?err=" . urlencode($res["error"]));
        }
    }

    redirectTo("../views/manager_views/bookingRequests.php?err=Invalid action");
}

redirectTo("../views/common_views/login.php?err=Access denied");
