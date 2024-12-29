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
<html lang="en">  

<head>  
    <title>Manage Ambulances</title>  
    <meta charset="utf-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">  
    <link rel="stylesheet" href="../css/navigation.css">  
    <link rel="stylesheet" href="../css/styleAmbulance.css">
    <style>
    .btn-warning-custom {
        background-color: #1D5748;
        border-color: #1D5748;
        color: white;
    }

    .btn-warning-custom:hover {
        background-color: #143D33;
        border-color: #143D33;
        color: white;
    }

    .btn-danger-custom {
        background-color: #B22222;
        border-color: #B22222;
        color: white;
    }

    .btn-danger-custom:hover {
        background-color: #7A1414;
        border-color: #7A1414;
    }

    .table thead th {
        background-color: #1D5748 !important; 
        color: white !important; 
    }

    .table tbody tr:nth-child(odd) {
        background-color:rgb(255, 250, 233); /* Nude color */
    }

    .table tbody tr:nth-child(even) {
        background-color: #FFFFFF; /* White color */
    }

</style>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>  
    <script src="../script/adminNavBar.js" defer></script>
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

    <!-- Navigation bar -->
    <div id="navbar"></div>

    <div class="container-xl py-4 px-5 rounded shadow" style="background-color: #F5F0DD;">  
        <h1 class="mb-4">Manage Ambulance</h1>  
        <div class="mb-3 d-flex justify-content-between align-items-center">  
            <a href="../mainPage/adminHomepage.html" class="btn btn-secondary btn-warning-custom">Back to Home</a>  
            <a href="addAmbulance.php" class="btn btn-success btn-warning-custom">Add Ambulance</a>  
        </div>  

        <!-- Search and Filter Section -->  
        <form method="GET" class="d-flex mb-4" >  
            <input type="text" name="search" class="form-control me-2" placeholder="Search by ID, type, capacity, or availability" value="<?php echo htmlspecialchars($search); ?>">  
            <select name="sort" class="form-select me-2">  
                <option value="" disabled selected>Sort by</option>  
                <option value="vehicleId" <?php if ($sort == 'vehicleId') echo 'selected'; ?>>Vehicle ID</option>  
                <option value="type" <?php if ($sort == 'type') echo 'selected'; ?>>Type</option>  
                <option value="capacity" <?php if ($sort == 'capacity') echo 'selected'; ?>>Capacity</option>  
                <option value="availability" <?php if ($sort == 'availability') echo 'selected'; ?>>Availability</option>  
            </select>  
            <button type="submit" class="btn btn-warning btn-sm btn-warning-custom">Search</button>  
        </form>  

        <table class="table table-bordered table-striped" >  
            <thead class="table-light" >  
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
                            <td class="text-center">  
                                <a href="editAmbulance.php?vehicleId=<?php echo $row['vehicleId']; ?>" class="btn btn-warning btn-sm btn-warning-custom" >Edit</a>  
                                <button class="btn btn-danger btn-sm btn-danger-custom" onclick="confirmDelete('<?php echo $row['vehicleId']; ?>')" >Delete</button>  
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
