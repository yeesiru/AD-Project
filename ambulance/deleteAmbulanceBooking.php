<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['vehicleId'])) {
    $vehicleId = $conn->real_escape_string($_GET['vehicleId']);

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Delete the booking record from ambulanceBooking table
        $sqlDeleteBooking = "DELETE FROM ambulanceBooking WHERE vehicleId = '$vehicleId'";
        if ($conn->query($sqlDeleteBooking) === TRUE) {
            // Update the availability status in the ambulance table
            $sqlUpdateAvailability = "UPDATE ambulance SET availability = 'available' WHERE vehicleId = '$vehicleId'";
            if ($conn->query($sqlUpdateAvailability) === TRUE) {
                // Commit transaction if both queries are successful
                $conn->commit();
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Deleted and Updated!',
                            text: 'Ambulance booking has been deleted and availability has been updated.',
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
                // If updating the availability fails, roll back the transaction
                $conn->rollback();
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to update availability: " . $conn->error . "',
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
            // If deleting the booking fails, roll back the transaction
            $conn->rollback();
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to delete booking: " . $conn->error . "',
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
    } catch (Exception $e) {
        // If any exception occurs, rollback the transaction
        $conn->rollback();
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred: " . $e->getMessage() . "',
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
                text: 'Invalid request: Vehicle ID missing.',
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
