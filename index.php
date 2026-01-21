<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: views/common_views/login.php");
    exit();
}

$role = $_SESSION["role"] ?? "";

if ($role === "manager") {
    header("Location: views/manager_views/home.php");
    exit();
}

if ($role === "customer") {
    header("Location: views/customer_views/home.php");
    exit();
}

session_unset();
session_destroy();
header("Location: views/common_views/login.php?err=Please login again");
exit();
