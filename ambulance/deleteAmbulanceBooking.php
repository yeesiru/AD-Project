<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['vehicleId'])) {
    $vehicleId = $conn->real_escape_string($_GET['vehicleId']); 

    // Begin transaction to ensure consistency
    $conn->begin_transaction();

    try {
        // Delete the booking record
        $deleteBookingSql = "DELETE FROM ambulanceBooking WHERE vehicleId = '$vehicleId'";
        if ($conn->query($deleteBookingSql) === TRUE) {
            
            // Update ambulance availability to 'Available'
            $updateAmbulanceSql = "UPDATE ambulance SET availability = 'Available' WHERE vehicleId = '$vehicleId'";
            if ($conn->query($updateAmbulanceSql) === TRUE) {
                
                // Commit transaction
                $conn->commit();

                echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Ambulance booking record has been deleted and availability updated successfully.',
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
                throw new Exception("Failed to update ambulance availability: " . $conn->error);
            }
        } else {
            throw new Exception("Failed to delete ambulance booking record: " . $conn->error);
        }
    } catch (Exception $e) {
        // Rollback transaction if an error occurs
        $conn->rollback();

        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Error!',
                    text: '" . addslashes($e->getMessage()) . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'manageAmbulanceBooking.php';
                });
            });
        </script>";
    }
} else {
    // Handle missing vehicleId
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

// Close database connection
$conn->close();
?>
