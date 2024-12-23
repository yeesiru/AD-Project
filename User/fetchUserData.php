<?php
include("../database/db_conn.php");
if (isset($_GET['userID'])) {
    $userId = $_GET['userID'];
    $stmt = $conn->prepare("SELECT * FROM User WHERE userID = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        http_response_code(404);
        echo json_encode(["error" => "User not found"]);
    }
}
?>
