<?php
// signup.php

// Allow access from frontend
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Connect to database
$host = "localhost";
$username = "root";
$password = "";
$database = "shinecab";

$conn = new mysqli($host, $username, $password, $database);

// Check DB connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}

// Get JSON input from frontend
$data = json_decode(file_get_contents("php://input"), true);

// DEBUG LINE (Optional)
file_put_contents("log.txt", print_r($data, true));  // Save to log.txt

// Check if required fields are present
if (isset($data['name'], $data['email'], $data['phone'], $data['password'])) {
    $name = $conn->real_escape_string($data['name']);
    $email = $conn->real_escape_string($data['email']);
    $phone = $conn->real_escape_string($data['phone']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT); // Hash password

    // Check if email already exists
    $check = $conn->query("SELECT id FROM users WHERE email = '$email'");

    if ($check->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already exists."]);
    } else {
        $sql = "INSERT INTO users (name, email, phone, password) 
                VALUES ('$name', '$email', '$phone', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Signup successful."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Signup failed."]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
}

$conn->close();
?>
