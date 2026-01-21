<?php

session_start();

function redirectTo($path)
{
    header("Location: " . $path);
    exit();
}

function clean($v)
{
    return trim($v);
}

if (!isset($_SESSION["user_id"])) {
    redirectTo("../views/common_views/login.php?err=Please login first");
}
if ($_SESSION["role"] !== "customer") {
    redirectTo("../views/common_views/login.php?err=Access denied");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirectTo("../views/customer_views/viewSlots.php?err=Invalid request");
}

$action = $_POST["action"] ?? "";

if ($action !== "view_slots_by_date") {
    redirectTo("../views/customer_views/viewSlots.php?err=Invalid action");
}

$date = clean($_POST["date"] ?? "");
if ($date === "") {
    redirectTo("../views/customer_views/viewSlots.php?err=Please select a date");
}

redirectTo("../views/customer_views/viewSlots.php?date=" . urlencode($date));
