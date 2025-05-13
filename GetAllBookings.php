<?php
// get_all_bookings.php

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
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit;
}

// Query to fetch all bookings with user and driver info
$sql = "
    SELECT 
        b.id AS booking_id,
        b.user_id,
        COALESCE(u.name, '') AS user_name,
        COALESCE(u.phone, '') AS user_phone,
        b.pickup_location,
        b.dropoff_location,
        b.date,
        b.time,
        b.status,
        b.driver_id,
        COALESCE(d.name, '') AS driver_name,
        COALESCE(d.phone, '') AS driver_phone
    FROM bookings b
    LEFT JOIN users u ON b.user_id = u.id
    LEFT JOIN drivers d ON b.driver_id = d.id
    ORDER BY b.id DESC
";
$result = $conn->query($sql);

if ($result === false) {
    echo json_encode(["status" => "error", "message" => "Query failed: " . $conn->error]);
    $conn->close();
    exit;
}

$bookings = [];

while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode(["status" => "success", "bookings" => $bookings]);

$conn->close();
?>
