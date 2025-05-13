<?php
// Allow frontend access
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// DB connection
$conn = new mysqli("localhost", "root", "", "shinecab");

// Check DB connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}

// Get and decode input JSON
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (isset($data['name'], $data['phone'], $data['vehicle_number'])) {
    $name = $conn->real_escape_string($data['name']);
    $phone = $conn->real_escape_string($data['phone']);
    $vehicle = $conn->real_escape_string($data['vehicle_number']);

    // Default status: available
    $status = 'available';

    // Insert driver
    $sql = "INSERT INTO drivers (name, phone, vehicle_number, status) 
            VALUES ('$name', '$phone', '$vehicle', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Driver added successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add driver."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
}

$conn->close();
?>
