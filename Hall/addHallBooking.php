<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['booked_by'];
    $contact = $_POST['contact'];
    $hallId = $_POST['hallId'];
    $date = $_POST['date'];
    $timeSlot = $_POST['timeSlot'];

    // Define the SQL query
    $sql = "INSERT INTO hallbooking (booked_by, hall_id, date, time_slot) 
            VALUES ('$name', '$hallId', '$date', '$timeSlot')";

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
                        window.location.href = 'viewHallBooking.php';
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
    <link rel="stylesheet" href="../css/navigation.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            <h1>Add Hall Booking</h1>
            <form id="addHallForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                
            <div class="form-group">
                    <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                    <input type="text" id="booked_by" name="booked_by" class="form-control" placeholder="John Doe" required>
                </div>

                <div class="form-group">
                    <label for="contact" class="form-label">Contact No<span class="text-danger">*</span></label>
                    <input type="text" id="contact" name="contact" class="form-control" placeholder="0123456789" required>
                </div>

                <div class="form-group">
                    <label for="hallId" class="form-label">Select Hall<span class="text-danger">*</span></label>
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
                    <label for="date" class="form-label">Date<span class="text-danger">*</span></label>
                    <input type="date" id="date" name="date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="timeSlot" class="form-label">Time<span class="text-danger">*</span></label>
                    <input type="time" id="timeSlot" name="timeSlot" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Book Hall</button>
                <a href="viewHallBooking.php" class="btn btn-secondary mt-2">Cancel</a>
            </form>
        </div>
    </div>

    <?php
    $conn->close();
    ?>
</body>

</html>
