<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $hallId = $_POST['hallId'];
    $date = $_POST['date'];
    $timeSlot = $_POST['timeSlot'];

    // Insert the hall booking entry into the database
    $sql = "INSERT INTO hallBooking (hall_id, booked_by, date, time_slot) 
            VALUES ('$hallId', '$name', '$date', '$timeSlot')";

    // Check for success or error
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Success!',
                    text: 'Hall booked successfully.',
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
                text: 'Error: Hall booking unsuccessful: " . $conn->error . "',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
    }
}

// Fetch available halls
$hallQuery = "SELECT hall_id, name, capacity FROM hall";
$hallResult = $conn->query($hallQuery);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Hall Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .container {
            margin-top: 50px;
        }

        .booking-form {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .booking-form h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            color: #555;
        }

        .form-control {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .btn-primary {
            background-color: #006d47;
            color: white;
            border: none;
            width: 100%;
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #004f36;
        }

        .btn-secondary {
            background-color: #777;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="./manageHallBooking.php" class="btn btn-secondary mb-3">Back</a>
        <h1 style="text-align: center;">Add Hall Booking</h1>

        <div class="booking-form">
            <form id="addBookingForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                
                <div class="form-group">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="contact" class="form-label">Contact No:</label>
                    <input type="text" id="contact" name="contact" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="hallId" class="form-label">Select Hall:</label>
                    <select id="hallId" name="hallId" class="form-select" required>
                        <option value="" disabled selected>Select a hall</option>
                        <?php
                        if ($hallResult->num_rows > 0) {
                            while ($row = $hallResult->fetch_assoc()) {
                                echo "<option value='" . $row['hall_id'] . "'>" . $row['name'] . " (Capacity: " . $row['capacity'] . ")</option>";
                            }
                        } else {
                            echo "<option value='' disabled>No halls available</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="date" class="form-label">Date:</label>
                    <input type="date" id="date" name="date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="timeSlot" class="form-label">Time Slot:</label>
                    <input type="text" id="timeSlot" name="timeSlot" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Book Hall</button>
            </form>
        </div>
    </div>

    <?php
    $conn->close();
    ?>
</body>

</html>
