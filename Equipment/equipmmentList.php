<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all equipment records
$sql = "SELECT * FROM equipment";
$result = $conn->query($sql);
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
</head>

<body>
    <!-- Navigation bar -->
    <div id="navbar"></div>

    <div class="container my-5">
        <h1 class="text-center">Equipment List</h1>

        <!-- Search and Filter Section -->
        <div class="search-bar mb-4">
            <input type="text" class="form-control d-inline-block me-2" style="width: 55%;" placeholder="Search Equipment">
            <button class="btn btn-outline-primary" style="width: 15%;">Search</button>
        </div>

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
                    if ($result->num_rows > 0) {
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

    <?php $conn->close(); ?>
</body>

</html>
