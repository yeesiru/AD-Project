<?php
include("../database/db_conn.php");

header('Content-Type: application/json');

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

if (isset($_GET['id'])) {
    $equipmentId = intval($_GET['id']);
    
    // Delete the equipment from the database
    $sql = "DELETE FROM equipment WHERE id = $equipmentId";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Equipment deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting record: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No ID provided.']);
}

$conn->close();

?>



