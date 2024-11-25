<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all ambulance booking records
$sql = "SELECT * FROM ambulanceBooking";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Ambulance Bookings</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/navigation.css">
    <link rel="stylesheet" href="../css/manageAmbulance.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(vehicleId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this booking record!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `deleteAmbulanceBooking.php?vehicleId=${vehicleId}`;
                }
            });
        }
    </script>
</head>

<body>
    <div class="container-xl" style="background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <h1>Ambulance Booking Management</h1>
        <a href="../index.html" class="btn btn-secondary mb-3">Back to Home</a>
        <a href="addAmbulanceBooking.php" class="btn btn-primary mb-3">Add New Booking</a>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Destination</th>
                        <th>Booking Time</th>
                        <th>Booking Date</th>
                        <th>Ambulance (Vehicle ID)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['contact']); ?></td>
                                <td><?php echo htmlspecialchars($row['destination']); ?></td>
                                <td><?php echo htmlspecialchars($row['booking_time']); ?></td>
                                <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['vehicleId']); ?></td>
                                <td>
                                    <a href="editAmbulanceBooking.php?vehicleId=<?php echo $row['vehicleId']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?php echo htmlspecialchars($row['vehicleId']); ?>')">Delete</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php $conn->close(); ?>
</body>

</html>
