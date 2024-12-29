<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch booking data using the booking ID
$vehicleId = $_GET['vehicleId'];
$sql = "SELECT * FROM ambulanceBooking WHERE vehicleId = '$vehicleId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "No such booking found!";
    exit;
}

// Handle form submission for updating the booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $destination = $_POST['destination'];
    $booking_time = $_POST['booking_time'];
    $booking_date = $_POST['booking_date'];
    // $vehicleId = $_POST['vehicleId'];

    $updateSql = "UPDATE ambulancebooking 
                  SET name='$name', contact='$contact', destination='$destination', 
                      booking_time='$booking_time', booking_date='$booking_date', vehicleId='$vehicleId' 
                  WHERE vehicleId='$vehicleId'";
    
    if ($conn->query($updateSql) === TRUE) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Success!',
                    text: 'Booking updated successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'viewAmbulanceBooking.php';
                    }
                });
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Error updating booking: " . $conn->error . "',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Booking</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/navigation.css">    
    <link rel="stylesheet" href="../css/manageAmbulanceBooking.css">
    <link rel="stylesheet" href="../css/styleAmbulance.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container bg-white p-4 rounded shadow">
        <a href="./viewAmbulanceBooking.php" class="btn btn-secondary mb-3">Back</a>
        <h1>Edit Booking Details</h1>

        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Name<span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required>
            </div>

            <div class="form-group mt-3">
                <label for="contact">Contact<span class="text-danger">*</span></label>
                <input type="text" id="contact" name="contact" class="form-control" value="<?php echo htmlspecialchars($row['contact']); ?>" required>
            </div>

            <div class="form-group mt-3">
                <label for="destination">Destination<span class="text-danger">*</span></label>
                <input type="text" id="destination" name="destination" class="form-control" value="<?php echo htmlspecialchars($row['destination']); ?>" required>
            </div>

            <div class="form-group mt-3">
                <label for="booking_time">Booking Time<span class="text-danger">*</span></label>
                <input type="time" id="booking_time" name="booking_time" class="form-control" value="<?php echo htmlspecialchars($row['booking_time'] ?? ''); ?>" required>
            </div>

            <div class="form-group mt-3">
                <label for="booking_date">Booking Date<span class="text-danger">*</span></label>
                <input type="date" id="booking_date" name="booking_date" class="form-control" value="<?php echo htmlspecialchars($row['booking_date'] ?? ''); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Update Booking</button>
            
        </form>
    </div>
</body>

</html>
