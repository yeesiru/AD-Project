<?php
include("../database/db_conn.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle selected equipment deletion
if (isset($_POST['deleteSelected'])) {
    if (!empty($_POST['equipmentToDelete'])) {
        $selectedEquipment = $_POST['equipmentToDelete'];
        foreach ($selectedEquipment as $bookingId) {
            $bookingId = intval($bookingId);

            // Retrieve the quantity and equipment_id before deletion
            $fetchQuery = "SELECT equipment_id, quantity FROM equipment_booking WHERE id = $bookingId";
            $fetchResult = $conn->query($fetchQuery);

            if ($fetchResult->num_rows > 0) {
                $row = $fetchResult->fetch_assoc();
                $equipmentId = intval($row['equipment_id']);
                $quantity = intval($row['quantity']);

                // Update the equipment table
                $updateEquipmentQuery = "UPDATE equipment SET quantity = quantity + $quantity WHERE id = $equipmentId";
                $conn->query($updateEquipmentQuery);

                // Delete the booking
                $deleteQuery = "DELETE FROM equipment_booking WHERE id = $bookingId";
                $conn->query($deleteQuery);
            }
        }
        echo "<script>alert('Selected equipment bookings deleted successfully.'); window.location.reload();</script>";
    } else {
        echo "<script>alert('No equipment selected for deletion.');</script>";
    }
}

// Handle all bookings deletion for a date
if (isset($_POST['deleteAllBookings'])) {
    $selectedDate = $conn->real_escape_string($_POST['deleteAllBookings']);

    // Retrieve all equipment_id and quantity for the selected date
    $fetchAllQuery = "SELECT equipment_id, quantity FROM equipment_booking WHERE booking_date = '$selectedDate'";
    $fetchAllResult = $conn->query($fetchAllQuery);

    if ($fetchAllResult->num_rows > 0) {
        while ($row = $fetchAllResult->fetch_assoc()) {
            $equipmentId = intval($row['equipment_id']);
            $quantity = intval($row['quantity']);

            // Update the equipment table
            $updateEquipmentQuery = "UPDATE equipment SET quantity = quantity + $quantity WHERE id = $equipmentId";
            $conn->query($updateEquipmentQuery);
        }

        // Delete all bookings for the selected date
        $deleteAllQuery = "DELETE FROM equipment_booking WHERE booking_date = '$selectedDate'";
        if ($conn->query($deleteAllQuery)) {
            echo "<script>alert('All bookings for the selected date have been deleted successfully.'); window.location.reload();</script>";
        } else {
            echo "<script>alert('Error deleting bookings for the selected date: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('No bookings found for the selected date.');</script>";
    }
}

// Fetch all bookings grouped by date
$sql = "SELECT eb.id AS booking_id, e.equipment, eb.quantity, eb.booking_date 
        FROM equipment_booking eb 
        JOIN equipment e ON eb.equipment_id = e.id 
        ORDER BY eb.booking_date";
$result = $conn->query($sql);

$bookingsByDate = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookingsByDate[$row['booking_date']][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Booked Equipment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center">Delete Booked Equipment</h1>

        <form method="POST">
            <?php if (!empty($bookingsByDate)): ?>
                <?php foreach ($bookingsByDate as $date => $bookings): ?>
                    <div class="mb-4 border p-3 rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>
                                Booking Date: <strong><?= htmlspecialchars($date) ?></strong>
                            </h5>
                            <!-- Delete all bookings for this date -->
                            <button type="submit" name="deleteAllBookings" value="<?= htmlspecialchars($date) ?>" class="btn btn-danger">
                                Delete All Bookings
                            </button>
                        </div>
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Equipment Name</th>
                                    <th>Booked Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="equipmentToDelete[]" value="<?= intval($booking['booking_id']) ?>">
                                        </td>
                                        <td><?= htmlspecialchars($booking['equipment']) ?></td>
                                        <td><?= intval($booking['quantity']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <!-- Delete selected equipment -->
                        <button type="submit" name="deleteSelected" class="btn btn-primary w-100">
                            Delete Selected Equipment
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No bookings found.</p>
            <?php endif; ?>
        </form>
    </div>

    <?php $conn->close(); ?>
</body>
</html>
