<?php
// login.php

// Allow access from frontend
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Connect to the database
$host = "localhost";
$username = "root";
$password = "";
$database = "shinecab";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate fields
if (isset($data['email'], $data['password'])) {
    $email = $conn->real_escape_string($data['email']);
    $password = $data['password'];

    // Fetch user by email
    $result = $conn->query("SELECT id, name, email, phone, password FROM users WHERE email = '$email'");

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            echo json_encode([
                "status" => "success",
                "message" => "Login successful.",
                "user" => [
                    "id" => $user['id'],
                    "name" => $user['name'],
                    "email" => $user['email'],
                    "phone" => $user['phone']
                ]
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid password."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
}

$conn->close();
?>
