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
    <link rel="stylesheet" href="../css/navigation.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
        background-color: #f8f9fa;
        font-family: 'Poppins', Arial, sans-serif;
        color: #343a40;
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

        /* Table Styling */
        table.table {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table.table th {
            background-color: BLACK;
            color: white;
            text-align: center;
        }

        table.table td {
            text-align: center;
            vertical-align: middle;
        }

        table.table td, table.table th {
            padding: 12px;
            font-size: 0.95rem;
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
    <nav>
        <ul class="sidebar">
            <li onclick="hideSidebar()"> <a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px"
                        viewBox="0 -960 960 960" width="24px" fill="black">
                        <path
                            d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
                    </svg></a></li>
            <li> <a href="#">Home</a></li>
            <li> <a href="#">Our Services</a></li>
            <li> <a href="#">User list</a></li>
            <li> <a href="#">Contact Us</a></li>
            <li> <a href="#">Logout</a></li>
        </ul>

        <ul style="justify-content: flex-end;">
            <li class="logo navbar-brand"> <a href="homepage.html">SJAM Connect</a></li>
            <li class="hideOnMobile"> <a href="#">Home</a></li>
            <li class="hideOnMobile"> <a href="#">Our Services</a></li>
            <li class="hideOnMobile"> <a href="#">User list</a></li>
            <li class="hideOnMobile"> <a href="#">Contact Us</a></li>
            <li class="hideOnMobile"> <a href="#">Logout</a></li>
            <li class="menuButton" onclick="showSideBar()"> <a href="#"><svg xmlns="http://www.w3.org/2000/svg"
                        height="24px" viewBox="0 -960 960 960" width="24px" fill="black">
                        <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
                    </svg></a></li>
        </ul>
    </nav>

    <div class="container mt-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Feedback Listings</h2>
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
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch feedback from the database
                    $sql = "SELECT id, serviceType, rating, feedbackText, status FROM feedback";
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
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No feedback found.</td></tr>";
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
