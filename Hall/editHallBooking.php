<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch booking data using the booking ID
$bookingId = $_GET['booking_id'];
$sql = "SELECT * FROM hallBooking WHERE booking_id = '$bookingId'";
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
    $date = $_POST['date'];
    $timeSlot = $_POST['timeSlot'];
    $hallId = $_POST['hallId'];
    $bookingId = $_POST['bookingId'];

    $updateSql = "UPDATE hallBooking 
                  SET booked_by='$name', contact='$contact', date='$date', 
                      time_slot='$timeSlot', hall_id='$hallId' 
                  WHERE booking_id='$bookingId'";
    
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
                        window.location.href = 'manageHallBooking.php';
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
    <title>Edit Hall Booking</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/navigation.css">
    <link rel="stylesheet" href="../css/manageAmbulanceBooking.css">    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container bg-white p-4 rounded shadow">
        
        <h1>Edit Hall Booking Details</h1>

        <form action="" method="POST">
            <input type="hidden" name="bookingId" value="<?php echo htmlspecialchars($row['booking_id']); ?>">

            <div class="form-group">
                <label for="name">Name<span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($row['booked_by']); ?>" required>
            </div>

            <div class="form-group mt-3">
                <label for="date">Date<span class="text-danger">*</span></label>
                <input type="date" id="date" name="date" class="form-control" value="<?php echo htmlspecialchars($row['date']); ?>" required>
            </div>

            <div class="form-group mt-3">
                <label for="timeSlot">Time<span class="text-danger">*</span></label>
                <input type="time" id="timeSlot" name="timeSlot" class="form-control" value="<?php echo htmlspecialchars($row['time_slot']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Update Booking</button>
            <a href="./manageHallBooking.php" class="btn btn-secondary mb-3">Back</a>
        </form>
    </div>
</body>

</html>
