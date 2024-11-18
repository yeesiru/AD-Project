<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['hall_id'])) {
    $hall_id = $conn->real_escape_string($_GET['hall_id']); // Sanitize the input to prevent SQL injection

    // Delete the hall record
    $sql = "DELETE FROM hall WHERE hall_id = '$hall_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Hall record has been deleted successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'manageHall.php';
                    }
                });
            });
        </script>";
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to delete hall record: " . $conn->error . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'manageHall.php';
                    }
                });
            });
        </script>";
    }
} else {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Error!',
                text: 'Invalid request: Hall ID missing.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'manageHall.php';
                }
            });
        });
    </script>";
}

$conn->close();
?>
