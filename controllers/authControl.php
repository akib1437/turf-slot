<?php
// controllers/authControl.php

session_start();

require_once __DIR__ . "/../models/userModel.php";

function redirect($path) {
    header("Location: " . $path);
    exit();
}

function clean($value) {
    return trim($value);
}

// Logout (GET)
if (isset($_GET["action"]) && $_GET["action"] === "logout") {
    session_unset();
    session_destroy();
    redirect("../views/common_views/login.php?msg=logout");
}

// Only POST below
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect("../views/common_views/login.php?err=Invalid request");
}

$action = isset($_POST["action"]) ? $_POST["action"] : "";

// REGISTER
if ($action === "register") {
    $name  = clean($_POST["name"] ?? "");
    $email = clean($_POST["email"] ?? "");
    $phone = clean($_POST["phone"] ?? "");
    $pass  = clean($_POST["password"] ?? "");
    $role  = clean($_POST["role"] ?? "");

    if ($name === "" || $email === "" || $phone === "" || $pass === "" || $role === "") {
        redirect("../views/common_views/register.php?err=Please fill all fields");
    }

    if ($role !== "customer" && $role !== "manager") {
        redirect("../views/common_views/register.php?err=Invalid role");
    }

    if (strlen($pass) < 6) {
        redirect("../views/common_views/register.php?err=Password must be at least 6 characters");
    }

    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $res = createUser($name, $email, $phone, $hash, $role);
    if ($res["ok"]) {
        redirect("../views/common_views/login.php?msg=registered");
    } else {
        $err = urlencode($res["error"]);
        redirect("../views/common_views/register.php?err=" . $err);
    }
}

// LOGIN
if ($action === "login") {
    $email = clean($_POST["email"] ?? "");
    $pass  = clean($_POST["password"] ?? "");

    if ($email === "" || $pass === "") {
        redirect("../views/common_views/login.php?err=Please enter email and password");
    }

    $user = getUserByEmail($email);

    if (!$user || !password_verify($pass, $user["password_hash"])) {
        redirect("../views/common_views/login.php?err=Invalid email or password");
    }

    // session set
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["name"] = $user["name"];
    $_SESSION["role"] = $user["role"];

    // redirect by role
    if ($user["role"] === "manager") {
        redirect("../views/manager_views/home.php");
    } else {
        redirect("../views/customer_views/home.php");
    }
}

// Unknown action
redirect("../views/common_views/login.php?err=Invalid action");
