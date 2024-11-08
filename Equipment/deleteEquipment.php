<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the equipment ID from the URL
$equipmentId = $_GET['id'];

// SQL statement to delete the equipment entry with the specified ID
$sql = "DELETE FROM equipment WHERE id = $equipmentId";
if ($conn->query($sql) === TRUE) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Success!',
                text: 'Equipment deleted successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'equipmentManage.html'; // Redirect to equipment management page
                }
            });
        });
    </script>";
} else {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Error deleting equipment record: " . $conn->error . "',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'equipmentManage.html'; // Redirect to equipment management page
            }
        });
    </script>";
}

$conn->close();
?>
