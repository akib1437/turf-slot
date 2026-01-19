<?php
// models/userModel.php

require_once __DIR__ . "/dbConnect.php";

function getUserByEmail($email) {
    $conn = dbConnect();

    $sql = "SELECT id, name, email, phone, password_hash, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    return $user ? $user : null;
}

function createUser($name, $email, $phone, $passwordHash, $role) {
    $conn = dbConnect();

    // If email exists, stop
    $existing = getUserByEmail($email);
    if ($existing) {
        return ["ok" => false, "error" => "Email already exists."];
    }

    $sql = "INSERT INTO users (name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $phone, $passwordHash, $role);

    $ok = $stmt->execute();

    $stmt->close();
    $conn->close();

    if ($ok) {
        return ["ok" => true];
    }
    return ["ok" => false, "error" => "Registration failed. Try again."];
}

// ===== Day 2 additions =====

function getUserById($id) {
    $conn = dbConnect();

    $sql = "SELECT id, name, email, phone, role FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    return $user ? $user : null;
}

function updateUserProfile($id, $name, $phone) {
    $conn = dbConnect();

    $sql = "UPDATE users SET name = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $phone, $id);

    $ok = $stmt->execute();

    $stmt->close();
    $conn->close();

    return $ok;
}

function updateUserPassword($id, $passwordHash) {
    $conn = dbConnect();

    $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $passwordHash, $id);

    $ok = $stmt->execute();

    $stmt->close();
    $conn->close();

    return $ok;
}
