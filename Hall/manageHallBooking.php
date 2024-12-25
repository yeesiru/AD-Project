<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for search and filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';

// Build the SQL query with search and filter
$sql = "SELECT b.booking_id, b.hall_id, h.name AS hall_name, b.booked_by, b.date, b.time_slot 
        FROM hallBooking b 
        INNER JOIN hall h ON b.hall_id = h.hall_id 
        WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (h.name LIKE '%" . $conn->real_escape_string($search) . "%' OR b.booked_by LIKE '%" . $conn->real_escape_string($search) . "%')";
}

if (!empty($filter_date)) {
    $sql .= " AND b.date = '" . $conn->real_escape_string($filter_date) . "'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Hall Bookings</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/hall.css">
    <link rel="stylesheet" href="../css/navigation.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDeleteBooking(booking_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This booking will be permanently deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `deleteHallBooking.php?booking_id=${booking_id}`;
                }
            });
        }
    </script>
</head>
<body>
<div class="container">
    <h1>Manage Hall Bookings</h1>
    <a href="../homepage.html" class="btn btn-secondary mb-3" style="background-color: #777; color: white; border: none;">Back to Home</a>
    <a href="addHallBooking.php" class="btn btn-primary mb-3" style="background-color: #006d47; color: white; border: none;">Add Booking</a>

    <!-- Search and Filter Form -->
    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search by hall name or booked by" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-4">
                <input type="date" name="filter_date" class="form-control" value="<?php echo htmlspecialchars($filter_date); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success">Apply</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr style="color: black">
                <th>Booking ID</th>
                <th>Hall Name</th>
                <th>Booked By</th>
                <th>Date</th>
                <th>Time Slot</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['hall_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['booked_by']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['time_slot']); ?></td>
                        <td>
                            <a href="editHallBooking.php?booking_id=<?php echo $row['booking_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <button class="btn btn-danger btn-sm" onclick="confirmDeleteBooking('<?php echo $row['booking_id']; ?>')">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No bookings found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
$conn->close();
?>
