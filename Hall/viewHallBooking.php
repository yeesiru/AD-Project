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
<html>  

<head>  
    <title>Manage Hall Booking</title>  
    <meta charset="utf-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">  
    <link rel="stylesheet" href="../css/navigation.css">  

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

    <script src="../script/officerNavBar.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>  
    <script>  
        function confirmDelete(booking_id) {  
            Swal.fire({  
                title: 'Are you sure?',  
                text: 'You will not be able to recover this booking record!',  
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

    <!-- Navigation bar --> 
    <div id="navbar"></div>

    <div class="container-xl py-4 px-5 rounded shadow" style="background-color: #F5F0DD;">  
        <h1 class="mb-4">Manage Hall Booking</h1>  
        <div class="mb-3 d-flex justify-content-between align-items-center">  
            <a href="../mainPage/officerHomepage.html" class="btn btn-secondary btn-warning-custom">Back to Home</a>  
            <a href="addHallBooking.php" class="btn btn-success btn-warning-custom">Add Booking</a>  
        </div>

        <!-- Search Bar Section -->
        <form method="GET" action="">
            <div class="search-filter">          
                <input style="width: 70%;" name="search" type="text" placeholder="Search By Name or Location" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="filter-button" style="width: 20%;">Search</button>
            </div>
        </form>  

        <div class="table-responsive">  
            <table class="table table-bordered table-striped">  
                <thead class="table-light">  
                    <tr>  
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
