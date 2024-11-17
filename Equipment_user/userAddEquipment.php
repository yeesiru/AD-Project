<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all equipment from the database
$sql = "SELECT * FROM equipment";
$result = $conn->query($sql);

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipmentId = $_POST['equipment_id'];
    $quantity = $_POST['quantity'];
    $selectedDate = $_POST['date'];

    // Check if the requested quantity is available
    $availabilityCheck = "SELECT quantity FROM equipment WHERE id = $equipmentId";
    $availabilityResult = $conn->query($availabilityCheck);
    $row = $availabilityResult->fetch_assoc();

    if ($quantity > $row['quantity']) {
        echo "<script>
                alert('Requested quantity exceeds available equipment.');
              </script>";
    } else {
        // Reduce the available quantity in the database
        $newQuantity = $row['quantity'] - $quantity;
        $updateQuery = "UPDATE equipment SET quantity = $newQuantity WHERE id = $equipmentId";

        if ($conn->query($updateQuery) === TRUE) {
            // Log the booking
            $logBooking = "INSERT INTO equipment_booking (equipment_id, quantity, booking_date) 
                           VALUES ($equipmentId, $quantity, '$selectedDate')";
            $conn->query($logBooking);

            echo "<script>
                    alert('Equipment booked successfully for $selectedDate.');
                    window.location.href = 'userAddEquipment.php';
                  </script>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Equipment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center">Available Equipment</h1>

        <!-- Date Selection -->
        <div class="my-4">
            <label for="date" class="form-label">Select Booking Date:</label>
            <input type="date" id="date" class="form-control" required>
        </div>

        <!-- Equipment Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Equipment Name</th>
                    <th>Available Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['type'] . "</td>";
                        echo "<td>" . $row['equipment'] . "</td>";
                        echo "<td>" . $row['quantity'] . "</td>";
                        echo "<td>
                                <form method='POST' action='' onsubmit='return validateDate()'>
                                    <input type='hidden' name='equipment_id' value='" . $row['id'] . "'>
                                    <input type='hidden' name='date' id='hiddenDate_" . $row['id'] . "'>
                                    <input type='number' name='quantity' min='1' max='" . $row['quantity'] . "' required>
                                    <button type='submit' class='btn btn-primary btn-sm'>Book</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No equipment available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function validateDate() {
            const dateInput = document.getElementById('date');
            
            if (!dateInput.value) {
                alert('Please select a date before booking.');
                return false;
            }

            // Pass the selected date to all forms
            const hiddenDates = document.querySelectorAll('[id^="hiddenDate_"]');
            hiddenDates.forEach(hiddenInput => {
                hiddenInput.value = dateInput.value;
            });

            return true;
        }
    </script>

    <?php $conn->close(); ?>
</body>

</html>
