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
    <script src="../script/officerNavBar.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

    <style>
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

        .message-container {
            display: flex;
            justify-content: center; /* Centers the message horizontally */
            align-items: center;
            height: 100px;
            margin-top: 20px;
        }
        .message {
            font-size: 1.1rem;
            color: #555;
            text-align: center;
        }
        #addFeedbackButton {
            display: flex;
            justify-content: center; /* Aligns the button to the right */
            margin-top: 20px;
        }
    
        button {
            background-color: #0B6623;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        button:hover {
            background-color: #1D8348;
        }

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

        tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
        }

        tbody tr:nth-child(even) {
        background-color: #ffffff;
        }

        @media (max-width: 768px) {
        table.table {
            font-size: 0.8rem;
        }

    }

    </style>

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
                    $sql = "SELECT id, serviceType, rating, feedbackText, status, admin_response FROM feedback";
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
    
    <!-- Button aligned to the right side of the screen -->
    <div id="addFeedbackButton">
        <button onclick="redirectToAddFeedback()">Add Feedback</button>
    </div>
    
    <?php
    $conn->close();
    ?>
    
</body>
</html>
