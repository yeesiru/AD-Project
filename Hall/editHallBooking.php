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
    $name = $_POST['booked_by'];
    $date = $_POST['date'];
    $timeSlot = $_POST['timeSlot'];
    $bookingId = $_POST['bookingId'];

    $updateSql = "UPDATE hallBooking 
                  SET booked_by='$name', date='$date', 
                      time_slot='$timeSlot' 
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
                        window.location.href = 'viewHallBooking.php';
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Add the additional styles here */
        .modal-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-form {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .modal-form h1 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 0.5rem;
            display: block;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #006d47;
            border: none;
            color: #fff;
            font-weight: bold;
            text-align: center;
            border-radius: 5px;
        }

        .btn-secondary {
            background-color: #777;
        }
    </style>
</head>

<body>
    <div class="modal-container">
        <div class="modal-form">
            <h1>Edit Hall Booking Details</h1>

            <form action="" method="POST">
                <input type="hidden" name="bookingId" value="<?php echo htmlspecialchars($row['booking_id']); ?>">

                <div class="form-group">
                    <label for="name">Name<span class="text-danger">*</span></label>
                    <input type="text" id="booked_by" name="booked_by" class="form-control" value="<?php echo htmlspecialchars($row['booked_by']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="date">Date<span class="text-danger">*</span></label>
                    <input type="date" id="date" name="date" class="form-control" value="<?php echo htmlspecialchars($row['date']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="timeSlot">Time<span class="text-danger">*</span></label>
                    <input type="time" id="timeSlot" name="timeSlot" class="form-control" value="<?php echo htmlspecialchars($row['time_slot']); ?>" required>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Update Booking</button>
                <a href="./viewHallBooking.php" class="btn btn-secondary mb-3">Back</a>
            </form>
        </div>
    </div>
</body>

</html>
