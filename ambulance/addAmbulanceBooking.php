<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $contact = $_POST['contact'];
        $destination = $_POST['destination'];
        $bookingTime = $_POST['time'];
        $bookingDate = $_POST['date'];
        $vehicleId = $_POST['vehicleId'];

    // Insert the booking entry into the database
    $sql = "INSERT INTO ambulanceBooking (name, contact, destination, booking_time, booking_date, vehicleId) 
            VALUES ('$name', '$contact', '$destination', '$bookingTime', '$bookingDate', '$vehicleId')";

    //test
    // Check for success or error
    if ($conn->query($sql) === TRUE) {
        // Mark the selected ambulance as unavailable
        $updateSql = "UPDATE ambulance SET availability = 'Unavailable' WHERE vehicleId = '$vehicleId'";
        $conn->query($updateSql);

        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Success!',
                    text: 'Ambulance booked successfully.',
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
                text: 'Error Ambulance booking unsuccessful: " . $conn->error . "',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
    }
}


// Fetch available ambulances
$ambulanceQuery = "SELECT vehicleId, type FROM ambulance WHERE availability = 'Available'";
$ambulanceResult = $conn->query($ambulanceQuery);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Ambulance Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/navigation.css">
    <link rel="stylesheet" href="../css/manageAmbulance.css">
    <link rel="stylesheet" href="../css/styleAmbulance.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .container{
            background-color: #F5F0DD;
        }
        button[type="submit"] {
        background-color: #1D5748;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
        font-weight: bold;
        }
        
        button[type="submit"]:hover {
            background-color: #143D33;
        }
        
        .custom-select-wrapper {
            position: relative;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="./viewAmbulanceBooking.php" class="btn btn-secondary mb-3 btn-warning-custom">Back</a>
        <h1 >Add Ambulance Booking</h1>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                
                <div class="form-group">
                    <label for="name" >Name<span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="contact" >Contact No<span class="text-danger">*</span></label>
                    <input type="text" id="contact" name="contact" class="form-control" pattern="\d{10,11}" title="Contact number must be 10 or 11 digits" required>
                </div>

                <div class="form-group">
                    <label for="destination">Destination<span class="text-danger">*</span></label>
                    <input type="text" id="destination" name="destination" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="date">Date<span class="text-danger">*</span></label>
                    <input type="date" id="date" name="date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="time">Time<span class="text-danger">*</span></label>
                    <input type="time" id="time" name="time" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="vehicleId">Select Ambulance<span class="text-danger">*</span></label>
                    <select id="vehicleId" name="vehicleId" class="form-select" required>
                        <option value="" disabled selected>Select an available ambulance</option>
                        <?php
                        if ($ambulanceResult->num_rows > 0) {
                            while ($row = $ambulanceResult->fetch_assoc()) {
                                echo "<option value='" . $row['vehicleId'] . "'>" . $row['vehicleId'] . " - " . $row['type'] . "</option>";
                            }
                        } else {
                            echo "<option value='' disabled>No ambulances available</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mt-2 btn-warning-custom">Book Ambulance</button>
            </form>
    </div>

    <?php
    $conn->close();
    ?>
</body>

</html>
