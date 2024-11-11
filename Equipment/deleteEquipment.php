<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $equipmentId = $_GET['id'];
    
    // Delete the equipment from the database
    $sql = "DELETE FROM equipment WHERE id = $equipmentId";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Equipment deleted successfully.');
                window.location.href = 'equipmentManage.php';
              </script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid equipment ID.";
}

$conn->close();
?>
