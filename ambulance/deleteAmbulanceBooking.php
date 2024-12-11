<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['vehicleId']) && !empty($_GET['vehicleId'])) {
    $vehicleId = $conn->real_escape_string($_GET['vehicleId']);

    // Delete the booking record
    $deleteSql = "DELETE FROM ambulanceBooking WHERE vehicleId = '$vehicleId'";

    if ($conn->query($deleteSql) === TRUE) {
        // Update the ambulance availability to 'Available'
        $updateSql = "UPDATE ambulance SET availability = 'Available' WHERE vehicleId = '$vehicleId'";
        
        if ($conn->query($updateSql) === TRUE) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Ambulance booking record has been deleted, and the ambulance is now available.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'manageAmbulanceBooking.php';
                    });
                });
            </script>";
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Booking deleted, but failed to update ambulance availability: " . $conn->error . "',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'manageAmbulanceBooking.php';
                    });
                });
            </script>";
        }
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to delete ambulance booking record: " . $conn->error . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'manageAmbulanceBooking.php';
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
            }).then(() => {
                window.location.href = 'manageAmbulanceBooking.php';
            });
        });
    </script>";
}

echo "<script>window.location.href = 'manageAmbulanceBooking.php';</script>";

$conn->close();
