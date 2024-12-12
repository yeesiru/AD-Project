<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['vehicleId'])) {
    $vehicleId = $conn->real_escape_string($_GET['vehicleId']); 

    // Delete the booking record
    $sql = "DELETE FROM ambulanceBooking WHERE vehicleId = '$vehicleId'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Ambulance booking record has been deleted successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'manageAmbulanceBooking.php';
                    }
                });
            });
        </script>";
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to delete ambulance booking record: " . $conn->error . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'manageAmbulanceBooking.php';
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
                text: 'Invalid request: Booking ID missing.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'manageAmbulanceBooking.php';
                }
            });
        });
    </script>";
}

echo "<script>window.location.href = 'manageAmbulanceBooking.php';</script>";

$conn->close();
?>
