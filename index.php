<?php
// index.php (project root)
session_start();

// If not logged in, go to login page
if (!isset($_SESSION["user_id"])) {
    header("Location: views/common_views/login.php");
    exit();
}

// If logged in, redirect based on role
$role = $_SESSION["role"] ?? "";

if ($role === "manager") {
    header("Location: views/manager_views/home.php");
    exit();
}

if ($role === "customer") {
    header("Location: views/customer_views/home.php");
    exit();
}

// Fallback (session role missing)
session_unset();
session_destroy();
header("Location: views/common_views/login.php?err=Please login again");
exit();
