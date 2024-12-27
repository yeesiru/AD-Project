<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch search term if set
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Prepare SQL query with a search filter
if (!empty($search)) {
    $sql = "SELECT * FROM equipment WHERE type LIKE ? OR equipment LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchParam = "%$search%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM equipment";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Equipment List</title>
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
            background-color: #1D5748;
            color: #FFFFFF;
        }

        .search-filter button:hover {
            background-color: #014520;
        }

        /* Add Equipment button styling */
        .mb-3 a.btn-success {
            background-color: #1D5748; 
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
            background-color: #1D5748; /* Dark green header */
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
        <form method="get" action="" class="search-filter">
            <input style="width: 70%;" type="text" name="search" placeholder="Search Equipment" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="filter-button" style="width: 20%;">Search</button>
        </form>

        <!-- Add Equipment Button -->
        <div class="mb-3 text-end">
            <a href="addEquipment.php" class="btn btn-success">Add Equipment</a>
        </div>

        <!-- Equipment Table -->
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-success">
                    <tr>
                        <th>Type</th>
                        <th>Equipment Name</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['equipment']) . "</td>";
                            echo "<td>" . intval($row['quantity']) . "</td>";
                            echo "<td>
                                    <a href='editEquipment.php?id=" . intval($row['id']) . "' class='btn btn-primary btn-sm me-2'>Edit</a>
                                    <a href='deleteEquipment.php?id=" . intval($row['id']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this equipment?\")'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No equipment found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    if (!empty($search)) {
        $stmt->close();
    }
    $conn->close();
    ?>
</body>

</html>
