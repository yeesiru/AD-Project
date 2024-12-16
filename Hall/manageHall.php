<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for search and filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

// Build the SQL query with search and filter
$sql = "SELECT * FROM hall WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (name LIKE '%" . $conn->real_escape_string($search) . "%' OR location LIKE '%" . $conn->real_escape_string($search) . "%')";
}

if (!empty($filter)) {
    $sql .= " AND capacity >= " . intval($filter);
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Halls</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/hall.css">
    <link rel="stylesheet" href="../css/navigation.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(hall_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this hall record!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `deleteHall.php?hall_id=${hall_id}`;
                }
            });
        }
    </script>
</head>
<body>
<div class="container">
    <h1>Hall Management</h1>
    <a href="../homepage.html" class="btn btn-secondary mb-3" style="background-color: #777; color: white; border: none;">Back to Home</a>
    <a href="addHall.php" class="btn btn-primary mb-3" style="background-color: #006d47; color: white; border: none;">Add Hall</a>

    <!-- Search and Filter Form -->
    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search by name or location" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <select name="filter" class="form-control">
                    <option value="">Filter by capacity</option>
                    <option value="50" <?php echo $filter == '50' ? 'selected' : ''; ?>>50+</option>
                    <option value="100" <?php echo $filter == '100' ? 'selected' : ''; ?>>100+</option>
                    <option value="200" <?php echo $filter == '200' ? 'selected' : ''; ?>>200+</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success">Apply</button> 
                <a href="manageHall.php" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr style="color: black">
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
                            <a href="editHall.php?hall_id=<?php echo $row['hall_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
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
</body>
</html>

<?php
$conn->close();
?>
