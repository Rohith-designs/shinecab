<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "shinecab");
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['booking_id'], $data['driver_id'])) {
    $booking_id = (int)$data['booking_id'];
    $driver_id = (int)$data['driver_id'];

    // Check if driver is available
    $driver_check = $conn->query("SELECT status FROM drivers WHERE id = $driver_id");
    if ($driver_check->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Driver not found."]);
        exit;
    }

    $driver = $driver_check->fetch_assoc();
    if ($driver['status'] !== 'available') {
        echo json_encode(["status" => "error", "message" => "Driver not available."]);
        exit;
    }

    // Assign driver to booking and update both tables
    $assign = $conn->query("UPDATE bookings SET driver_id = $driver_id, status = 'assigned' WHERE id = $booking_id");
    $update_driver = $conn->query("UPDATE drivers SET status = 'busy' WHERE id = $driver_id");

    if ($assign && $update_driver) {
        echo json_encode(["status" => "success", "message" => "Driver assigned successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to assign driver."]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
}

$conn->close();
?>
