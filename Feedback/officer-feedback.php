<?php
include("../database/db_conn.php"); // Include the database connection file
?>

<!DOCTYPE html>
<html>

<head>
    <title>Feedback Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/navigation.css">
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href="../css/officer-feedback.css">
    <script src="../script/officerNavBar.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        function showSideBar() {
            const sidebar = document.querySelector('.sidebar')
            sidebar.style.display = 'flex'
        }

        function hideSidebar() {
            const sidebar = document.querySelector('.sidebar')
            sidebar.style.display = 'none'
        }

        function redirectToAddFeedback() {
        window.location.href = 'addFeedback.php';
    }
    </script>
</head>

<body>
    <!-- Navigation bar -->
    <div id="navbar"></div>

    <div class="container mt-4">
        <!-- Page Header -->
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
            <!-- Button aligned to the right side of the screen -->
            <div id="addFeedbackButton">
                <button onclick="redirectToAddFeedback()">Add Feedback</button>
            </div>
        </div>

        <!-- Feedback Table -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                <tr>
                        <th>Service Type</th>
                        <th>Rating</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Response</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch feedback from the database
                    $sql = "SELECT id, serviceType, rating, feedbackText, admin_response, status FROM feedback WHERE 1=1";
                    
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

                            // Apply conditional styling to the status column
                            $statusClass = ($row['status'] === 'pending') ? 'status-pending' : 'status-responded';
                            echo "<td class='$statusClass'>" . htmlspecialchars(strtoupper($row['status'])) . "</td>";
                            echo "<td>" . htmlspecialchars($row['admin_response']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No feedback found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php
    $conn->close();
    ?>
    
</body>
</html>
