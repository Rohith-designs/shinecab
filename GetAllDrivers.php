<?php
// get_all_drivers.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "shinecab";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}

$result = $conn->query("SELECT * FROM drivers");

$drivers = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $drivers[] = $row;
    }
    echo json_encode(["status" => "success", "drivers" => $drivers]);
} else {
    echo json_encode(["status" => "success", "drivers" => []]);
}

$conn->close();
?>
