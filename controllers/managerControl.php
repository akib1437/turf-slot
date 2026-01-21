<?php

session_start();
require_once __DIR__ . "/../models/slotModel.php";

function redirectTo($path) {
    header("Location: " . $path);
    exit();
}

function clean($v) {
    return trim($v);
}

function isValidTimeRange($start, $end) {
    
    return strtotime($end) > strtotime($start);
}


if (!isset($_SESSION["user_id"])) {
    redirectTo("../views/common_views/login.php?err=Please login first");
}
if ($_SESSION["role"] !== "manager") {
    redirectTo("../views/common_views/login.php?err=Access denied");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirectTo("../views/manager_views/home.php?err=Invalid request");
}

$action = $_POST["action"] ?? "";

if ($action === "add_slot") {
    $date = clean($_POST["slot_date"] ?? "");
    $start = clean($_POST["start_time"] ?? "");
    $end = clean($_POST["end_time"] ?? "");

    if ($date === "" || $start === "" || $end === "") {
        redirectTo("../views/manager_views/manageSlots.php?date=" . urlencode($date) . "&err=Please fill all fields");
    }

    if (!isValidTimeRange($start, $end)) {
        redirectTo("../views/manager_views/manageSlots.php?date=" . urlencode($date) . "&err=End time must be after start time");
    }

    $res = createSlot($date, $start, $end);
    if ($res["ok"]) {
        redirectTo("../views/manager_views/manageSlots.php?date=" . urlencode($date) . "&msg=slot_added");
    } else {
        redirectTo("../views/manager_views/manageSlots.php?date=" . urlencode($date) . "&err=" . urlencode($res["error"]));
    }
}

if ($action === "delete_slot") {
    $slotId = (int)($_POST["slot_id"] ?? 0);
    $date = clean($_POST["slot_date"] ?? "");

    if ($slotId <= 0) {
        redirectTo("../views/manager_views/manageSlots.php?date=" . urlencode($date) . "&err=Invalid slot");
    }

    $ok = deleteSlot($slotId);
    if ($ok) {
        redirectTo("../views/manager_views/manageSlots.php?date=" . urlencode($date) . "&msg=slot_deleted");
    } else {
        redirectTo("../views/manager_views/manageSlots.php?date=" . urlencode($date) . "&err=Delete failed");
    }
}

if ($action === "update_slot") {
    $slotId = (int)($_POST["slot_id"] ?? 0);
    $date = clean($_POST["slot_date"] ?? "");
    $start = clean($_POST["start_time"] ?? "");
    $end = clean($_POST["end_time"] ?? "");

    if ($slotId <= 0 || $date === "" || $start === "" || $end === "") {
        redirectTo("../views/manager_views/manageSlots.php?date=" . urlencode($date) . "&err=Please fill all fields");
    }

    if (!isValidTimeRange($start, $end)) {
        redirectTo("../views/manager_views/editSlot.php?id=" . $slotId . "&err=End time must be after start time");
    }

    $res = updateSlot($slotId, $date, $start, $end);
    if ($res["ok"]) {
        redirectTo("../views/manager_views/manageSlots.php?date=" . urlencode($date) . "&msg=slot_updated");
    } else {
        redirectTo("../views/manager_views/editSlot.php?id=" . $slotId . "&err=" . urlencode($res["error"]));
    }
}

redirectTo("../views/manager_views/manageSlots.php?err=Invalid action");
