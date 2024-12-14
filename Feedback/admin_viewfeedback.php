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
    <script src="../script/adminNavBar.js" defer></script>

    <style>
        /* General Styling */
        body {
        background-color: #f8f9fa;
        font-family: 'Poppins', Arial, sans-serif;
        color: #343a40;
        }

        h2 {
            color: #343a40;
            font-weight: bold;
        }

        nav li:first-child {
            margin-right: auto;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #FFF8EB;
            text-align: left;
        }

        table th,
        table td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f4f4f4;
        }

        .table-responsive {
            background-color: #F5F0DD;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: auto;
        }

        thead th {
            background-color: #FFF8EB;
            /* Slightly darker grey for header */
            padding: 10px;
            font-weight: bold;
            border-bottom: 2px solid #ccc;
            color: #F5F0DD;
        }

        tbody td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            color: #1D5748;
        }

        tbody tr:nth-child(even) {
            background-color: #fafafa;
            /* Alternate row colors */
        }

        table thead{
            background-color: #1D5748;
            color: #F5F0DD;
        }

        table tbody{
            background-color: #f8f6f2b8;
        }

        .table-actions a {
            margin-right: 8px;
        }

        /* Status Styling */
        .status-pending {
            color: red;
            font-weight: bold;
        }

        .status-responded {
            color: green;
            font-weight: bold;
        }

        /* Button Styling */
        .btn-search {
            color: white;
            background-color: #017b56;
            transition: all 0.3s ease-in-out;
            width: 200px;
            border-radius: 5px;
        }

        .btn-search:hover {
            color: white;
            background-color: #1D5748;
            transition: all 0.3s ease-in-out;
            width: 200px;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        /* Hover Effects */
        tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
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
