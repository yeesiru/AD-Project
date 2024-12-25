<?php  
include("../database/db_conn.php");

if ($conn->connect_error) {  
    die("Connection failed: " . $conn->connect_error);  
}

// Set default query
$search = $_GET['search'] ?? '';  
$sort = $_GET['sort'] ?? '';  

$sql = "SELECT * FROM ambulanceBooking WHERE name LIKE '%$search%' OR contact LIKE '%$search%' OR destination LIKE '%$search%' OR vehicleId LIKE '%$search%'";  

if ($sort) {  
    $sql .= " ORDER BY $sort";  
}

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
        <a href="../homepage.html" class="btn btn-secondary mb-3">Back to Home</a>  
        <a href="addAmbulanceBooking.php" class="btn btn-success mb-3">Add Booking</a>  

        <!-- Search and Filter Section -->  
        <form method="GET" class="d-flex mb-3">  
            <input type="text" name="search" class="form-control me-2" placeholder="Search by Name, Contact, Destination, or Vehicle ID" value="<?php echo htmlspecialchars($search); ?>">  
            <select name="sort" class="form-select me-2">  
                <option value="" disabled selected>Sort by</option>  
                <option value="name" <?php if ($sort == 'name') echo 'selected'; ?>>Name</option>  
                <option value="contact" <?php if ($sort == 'contact') echo 'selected'; ?>>Contact</option>  
                <option value="destination" <?php if ($sort == 'destination') echo 'selected'; ?>>Destination</option>  
                <option value="booking_date" <?php if ($sort == 'booking_date') echo 'selected'; ?>>Booking Date</option>  
                <option value="booking_time" <?php if ($sort == 'booking_time') echo 'selected'; ?>>Booking Time</option>  
                <option value="vehicleId" <?php if ($sort == 'vehicleId') echo 'selected'; ?>>Vehicle ID</option>  
            </select>  
            <button type="submit" class="btn btn-primary">Search</button>  
        </form>  

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
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?php echo $row['vehicleId']; ?>')">Delete</button>  
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
