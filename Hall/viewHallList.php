<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all equipment records
$sql = "SELECT * FROM equipment";
$result = $conn->query($sql);

// Initialize variables for search and filter
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build the SQL query with search and filter
$sql = "SELECT * FROM hall WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (name LIKE '%" . $conn->real_escape_string($search) . "%' OR location LIKE '%" . $conn->real_escape_string($search) . "%')";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hall List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/navigation.css">
    <link rel="stylesheet" href="../css/homepage.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../script/adminNavBar.js" defer></script>
    <style>
        /* General styling for the page */
        body {
            font-family: Arial, sans-serif;
            color: #1D5748; /* Dark green text */
        }

        .search-filter {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            justify-content: center;
        }

        .search-filter input,
        .search-filter button {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #DDD;
            border-radius: 5px;
        }

        .search-filter button {
            background-color: #017b56;
            color: #FFFFFF;
        }

        .search-filter button:hover {
            background-color: #014520;
        }

        /* Add Equipment button styling */
        .mb-3 a.btn-success {
            background-color: #017b56; 
            color: none;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
        }

        .mb-3 a.btn-success:hover {
            background-color: #014520; /* Darker green */
        }

        /* Table styling */
        .table-container {
            background-color: #F5F0DD;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: #FFF8EB; /* Light cream table background */
            text-align: left;
        }

        .table th,
        .table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .table-success th {
            background-color: #017b56; /* Dark green header */
            color: #FFFFFF; /* White text */
            padding: 12px;
            font-weight: bold;
            text-align: center;
        }

        .table tbody td {
            color: black; 
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .table tbody tr:nth-child(even) {
            background-color: #fafafa; /* Light alternate row */
        }

        /* Action button styling */
        .btn-primary {
            background-color: #1D5748; /* Dark green for Edit button */
            border: none;
            color: #FFFFFF;
        }

        .btn-primary:hover {
            background-color: #014520; /* Darker green */
        }

        .btn-danger {
            background-color: #B22222; /* Red for Delete button */
            border: none;
            color: #FFFFFF;
        }

        .btn-danger:hover {
            background-color: #8B0000; /* Darker red */
        }

    </style>
</head>

<body>
    <!-- Navigation bar -->
    <div id="navbar"></div>

    <div class="container my-5">

        <!-- Search Bar Section -->
        <form method="GET" action="">
            <div class="search-filter">          
                <input style="width: 70%;" name="search" type="text" placeholder="Search By Name or Location" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="filter-button" style="width: 20%;">Search</button>
            </div>
        </form>

        <!-- Add Hall Button -->
        <div class="mb-3 text-end">
            <a href="addHall.php" class="btn btn-success">Add Hall</a>
        </div>

        <!-- Hall Table -->
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-success">
                    <tr>
                    <th>Hall ID</th>
                    <th>Name</th>
                    <th>Capacity</th>
                    <th>Location</th>
                    <th>Facilities</th>
                    <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['hall_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['capacity']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo htmlspecialchars($row['facility']); ?></td>
                            <td>
                                <a href="editHall.php?hall_id=<?php echo $row['hall_id']; ?>" class="btn btn-primary btn-sm me-2">Edit</a>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?php echo $row['hall_id']; ?>')">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No halls found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php $conn->close(); ?>
</body>

</html>
