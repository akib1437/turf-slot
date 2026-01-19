<?php
// controllers/profileControl.php

session_start();
require_once __DIR__ . "/../models/userModel.php";

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

if ($action !== "update_profile") {
    redirectTo("../views/common_views/login.php?err=Invalid action");
}

$name = clean($_POST["name"] ?? "");
$phone = clean($_POST["phone"] ?? "");
$newPass = clean($_POST["new_password"] ?? "");

if ($name === "" || $phone === "") {
    // redirect back to correct profile page
    if ($_SESSION["role"] === "manager") {
        redirectTo("../views/manager_views/profile.php?err=Please fill all fields");
    } else {
        redirectTo("../views/customer_views/profile.php?err=Please fill all fields");
    }
}

$userId = (int)$_SESSION["user_id"];

// Update basic info
$okInfo = updateUserProfile($userId, $name, $phone);

if (!$okInfo) {
    if ($_SESSION["role"] === "manager") {
        redirectTo("../views/manager_views/profile.php?err=Profile update failed");
    } else {
        redirectTo("../views/customer_views/profile.php?err=Profile update failed");
    }
}

// If user typed password, update it
if ($newPass !== "") {
    if (strlen($newPass) < 6) {
        if ($_SESSION["role"] === "manager") {
            redirectTo("../views/manager_views/profile.php?err=Password must be at least 6 characters");
        } else {
            redirectTo("../views/customer_views/profile.php?err=Password must be at least 6 characters");
        }
    }

    $hash = password_hash($newPass, PASSWORD_DEFAULT);
    $okPass = updateUserPassword($userId, $hash);

    if (!$okPass) {
        if ($_SESSION["role"] === "manager") {
            redirectTo("../views/manager_views/profile.php?err=Password update failed");
        } else {
            redirectTo("../views/customer_views/profile.php?err=Password update failed");
        }
    }
}

// Update session name (so navbar/home shows updated name)
$_SESSION["name"] = $name;

// Redirect success
if ($_SESSION["role"] === "manager") {
    redirectTo("../views/manager_views/profile.php?msg=updated");
} else {
    redirectTo("../views/customer_views/profile.php?msg=updated");
}
