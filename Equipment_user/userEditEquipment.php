<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch distinct booking dates for grouping
$dateQuery = "SELECT DISTINCT booking_date FROM equipment_booking ORDER BY booking_date ASC";
$dateResult = $conn->query($dateQuery);

// Update the booking if the user submits the form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingId = intval($_POST['booking_id']);
    $newQuantity = intval($_POST['quantity']);
    
    // Fetch the original booking details
    $bookingQuery = "SELECT equipment_id, quantity FROM equipment_booking WHERE id = $bookingId";
    $bookingResult = $conn->query($bookingQuery);

    if ($bookingResult->num_rows > 0) {
        $booking = $bookingResult->fetch_assoc();
        $equipmentId = $booking['equipment_id'];
        $originalQuantity = $booking['quantity'];

        // Adjust the available quantity in the `equipment` table
        $difference = $newQuantity - $originalQuantity;
        $updateEquipment = "UPDATE equipment SET quantity = quantity - $difference WHERE id = $equipmentId";

        if ($conn->query($updateEquipment) === TRUE) {
            // Update the booking quantity
            $updateBooking = "UPDATE equipment_booking SET quantity = $newQuantity WHERE id = $bookingId";
            if ($conn->query($updateBooking) === TRUE) {
                echo "<script>
                        alert('Booking updated successfully.');
                        window.location.href = 'userEditEquipment.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Error updating booking: " . $conn->error . "');
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Error updating equipment availability: " . $conn->error . "');
                  </script>";
        }
    } else {
        echo "<script>
                alert('Invalid booking ID.');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bookings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center">Edit Your Bookings</h1>

        <?php
        if ($dateResult->num_rows > 0) {
            while ($dateRow = $dateResult->fetch_assoc()) {
                $bookingDate = $dateRow['booking_date'];

                // Fetch bookings for the current date
                $bookingQuery = "SELECT eb.id AS booking_id, e.type, e.equipment, eb.quantity
                                 FROM equipment_booking eb
                                 JOIN equipment e ON eb.equipment_id = e.id
                                 WHERE eb.booking_date = '$bookingDate'";
                $bookingResult = $conn->query($bookingQuery);

                if ($bookingResult->num_rows > 0) {
                    echo "<h3 class='mt-4'>Bookings for " . date('d-m-Y', strtotime($bookingDate)) . "</h3>";
                    echo "<table class='table table-bordered'>";
                    echo "<thead>
                            <tr>
                                <th>Type</th>
                                <th>Equipment Name</th>
                                <th>Quantity</th>
                                <th>Actions</th>
                            </tr>
                          </thead>";
                    echo "<tbody>";

                    while ($row = $bookingResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['equipment']) . "</td>";
                        echo "<td>" . intval($row['quantity']) . "</td>";
                        echo "<td>
                                <form method='POST' action=''>
                                    <input type='hidden' name='booking_id' value='" . intval($row['booking_id']) . "'>
                                    <input type='number' name='quantity' min='1' value='" . intval($row['quantity']) . "' required>
                                    <button type='submit' class='btn btn-primary btn-sm'>Update</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";
                }
            }
        } else {
            echo "<p class='text-center text-danger'>No bookings found.</p>";
        }
        ?>

    </div>

    <?php $conn->close(); ?>
</body>

</html>
