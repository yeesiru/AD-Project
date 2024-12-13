<?php  
include("../database/db_conn.php");

if ($conn->connect_error) {  
    die("Connection failed: " . $conn->connect_error);  
}

// Set default query
$search = $_GET['search'] ?? '';  
$sort = $_GET['sort'] ?? '';  

$sql = "SELECT * FROM ambulance WHERE type LIKE '%$search%' OR vehicleId LIKE '%$search%' OR capacity LIKE '%$search%' OR availability LIKE '%$search%'";  

if ($sort) {  
    $sql .= " ORDER BY $sort";  
}

$result = $conn->query($sql);  
?>  

<!DOCTYPE html>  
<html>  

<head>  
    <title>Manage Ambulances</title>  
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
                text: 'You will not be able to recover this record!',  
                icon: 'warning',  
                showCancelButton: true,  
                confirmButtonText: 'Yes, delete it!',  
                cancelButtonText: 'Cancel'  
            }).then((result) => {  
                if (result.isConfirmed) {  
                    window.location.href = `deleteAmbulance.php?vehicleId=${vehicleId}`;  
                }  
            });  
        }  
    </script>  
</head>  

<body>  
    <div class="container-xl" style="background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">  
        <h1>Ambulance Management</h1>  
        <a href="../homepage.html" class="btn btn-secondary mb-3">Back to Home</a>  
        <a href="addAmbulance.php" class="btn btn-primary mb-3">Add Ambulance</a>  

        <!-- Search and Filter Section -->  
        <form method="GET" class="d-flex mb-3">  
            <input type="text" name="search" class="form-control me-2" placeholder="Search by ID, type, capacity, or availability" value="<?php echo htmlspecialchars($search); ?>">  
            <select name="sort" class="form-select me-2">  
                <option value="" disabled selected>Sort by</option>  
                <option value="vehicleId" <?php if ($sort == 'vehicleId') echo 'selected'; ?>>Vehicle ID</option>  
                <option value="type" <?php if ($sort == 'type') echo 'selected'; ?>>Type</option>  
                <option value="capacity" <?php if ($sort == 'capacity') echo 'selected'; ?>>Capacity</option>  
                <option value="availability" <?php if ($sort == 'availability') echo 'selected'; ?>>Availability</option>  
            </select>  
            <button type="submit" class="btn btn-success">Search</button>  
        </form>  

        <table class="table table-bordered table-striped">  
            <thead>  
                <tr>  
                    <th>Vehicle ID</th>  
                    <th>Type</th>  
                    <th>Capacity</th>  
                    <th>Availability</th>  
                    <th>Actions</th>  
                </tr>  
            </thead>  
            <tbody>  
                <?php if ($result->num_rows > 0): ?>  
                    <?php while ($row = $result->fetch_assoc()): ?>  
                        <tr>  
                            <td><?php echo htmlspecialchars($row['vehicleId']); ?></td>  
                            <td><?php echo htmlspecialchars($row['type']); ?></td>  
                            <td><?php echo htmlspecialchars($row['capacity']); ?></td>  
                            <td><?php echo htmlspecialchars($row['availability']); ?></td>  
                            <td>  
                                <a href="editAmbulance.php?vehicleId=<?php echo $row['vehicleId']; ?>" class="btn btn-warning btn-sm">Edit</a>  
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?php echo $row['vehicleId']; ?>')">Delete</button>  
                            </td>  
                        </tr>  
                    <?php endwhile; ?>  
                <?php else: ?>  
                    <tr>  
                        <td colspan="5" class="text-center">No ambulances found.</td>  
                    </tr>  
                <?php endif; ?>  
            </tbody>  
        </table>  
    </div>  

    <?php $conn->close(); ?>  
</body>  

</html>  
