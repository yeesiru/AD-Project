<?php
session_start();
header("Content-Type: application/json");
include("../database/db_conn.php");

// Ensure user is logged in
if (!isset($_SESSION['userID'])) {
    echo json_encode(["success" => false, "message" => "User is not logged in."]);
    exit;
}

$userID = $_SESSION['userID'];
$data = json_decode(file_get_contents("php://input"), true);

$currentPassword = $data['currentPassword'] ?? '';
$newPassword = $data['newPassword'] ?? '';

// Validate inputs
if (empty($currentPassword) || empty($newPassword)) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

// Validate password strength
if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $newPassword)) {
    echo json_encode(["success" => false, "message" => "New password must meet complexity requirements."]);
    exit;
}

// Fetch current password hash
$sql = "SELECT password FROM User WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userID);
$stmt->execute();
$stmt->bind_result($hashedPassword);
$stmt->fetch();
$stmt->close();

// Verify the current password
if (!password_verify($currentPassword, $hashedPassword)) {
    echo json_encode(["success" => false, "message" => "Current password is incorrect."]);
    exit;
}

// Hash the new password
$newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Update password in the database
$sql = "UPDATE User SET password = ? WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $newHashedPassword, $userID);
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Password updated successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update password."]);
}
$stmt->close();
$conn->close();
?>
