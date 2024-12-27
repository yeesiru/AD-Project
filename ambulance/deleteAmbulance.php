<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['vehicleId'])) {
    $vehicleId = $conn->real_escape_string($_GET['vehicleId']); // Sanitize input

    // Delete the ambulance record
    $sql = "DELETE FROM ambulance WHERE vehicleId = '$vehicleId'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Ambulance record has been deleted successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    setTimeout(() => {
                        window.location.href = 'manageAmbulance.php';
                    }, 100);
                });
            });
        </script>";
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to delete ambulance record: " . $conn->error . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    setTimeout(() => {
                        window.location.href = 'manageAmbulance.php';
                    }, 100);
                });
            });
        </script>";
    }
} else {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Error!',
                text: 'Invalid request: Vehicle ID missing.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                setTimeout(() => {
                    window.location.href = 'manageAmbulance.php';
                }, 100);
            });
        });
    </script>";
}

echo "<script>window.location.href = 'viewAmbulance.php';</script>";

$conn->close();
?>
