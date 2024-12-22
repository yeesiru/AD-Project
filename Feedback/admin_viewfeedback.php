<?php
include("../database/db_conn.php"); // Include the database connection file
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Feedback Listing</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/navigation.css">
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href="../css/admin_viewfeedback.css">
    <script src="../script/adminNavBar.js" defer></script>

</head>
<body>
    <div id="navbar"></div>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Feedback</h2>
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by rating" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <select name="filter" class="form-select me-2">
                    <option value="">All Services</option>
                    <option value="hall" <?php echo isset($_GET['filter']) && $_GET['filter'] === 'hall' ? 'selected' : ''; ?>>Hall</option>
                    <option value="ambulance" <?php echo isset($_GET['filter']) && $_GET['filter'] === 'ambulance' ? 'selected' : ''; ?>>Ambulance</option>
                    <option value="equipment" <?php echo isset($_GET['filter']) && $_GET['filter'] === 'equipment' ? 'selected' : ''; ?>>Equipment</option>
                </select>
                <button type="submit" class="btn-search">Search</button>
            </form>

            <a href="feedbackreport.php" class="btn btn-success">Generate Feedback Report</a>
        </div>

        
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Service Type</th>
                        <th>Rating</th>
                        <th>Message</th>
                        <th>Submitted Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT id, serviceType, rating, feedbackText, submitted_at, status FROM feedback WHERE 1=1";
                    
                    // Search by Rating
                    if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
                        $search = $conn->real_escape_string(trim($_GET['search']));
                        $sql .= " AND rating LIKE '%$search%'";
                    }

                    // Filter by Service Type
                    if (isset($_GET['filter']) && !empty($_GET['filter'])) {
                        $filter = $conn->real_escape_string($_GET['filter']);
                        $sql .= " AND serviceType = '$filter'";
                    }

                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['serviceType']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['rating']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['feedbackText']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['submitted_at']) . "</td>";
                            $statusClass = ($row['status'] === 'pending') ? 'status-pending' : 'status-responded';
                            echo "<td class='$statusClass'>" . htmlspecialchars(strtoupper($row['status'])) . "</td>";
                            echo "<td class='table-actions'>
                            <a href='replyFeedback.php?feedback_id=" . htmlspecialchars($row['id']) . "' class='btn btn-primary'>Reply</a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No feedback found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php $conn->close(); ?>
</body>
</html>
