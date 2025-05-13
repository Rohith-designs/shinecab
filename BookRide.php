<?php
// book_ride.php

// Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "shinecab";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (
    isset($data['user_id'], $data['pickup_location'], $data['dropoff_location'], $data['date'], $data['time'])
) {
    $user_id = $conn->real_escape_string($data['user_id']);
    $pickup = $conn->real_escape_string($data['pickup_location']);
    $dropoff = $conn->real_escape_string($data['dropoff_location']);
    $date = $conn->real_escape_string($data['date']);
    $time = $conn->real_escape_string($data['time']);

    $sql = "INSERT INTO bookings (user_id, pickup_location, dropoff_location, date, time)
            VALUES ('$user_id', '$pickup', '$dropoff', '$date', '$time')";
error_log(print_r($data, true));
    if ($conn->query($sql)) {
        echo json_encode([
            "status" => "success",
            "message" => "Booking successful. Awaiting confirmation."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to book ride."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Missing required fields."
    ]);
}

$conn->close();
?>
