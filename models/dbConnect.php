<?php

function dbConnect() {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "turfslot_db"; 

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Database Connection Failed: " . $conn->connect_error);
    }

    return $conn;
}
