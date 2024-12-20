<?php
include("../database/db_conn.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle deletion of all bookings for a specific date
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_date'])) {
    $deleteDate = $conn->real_escape_string($_POST['delete_date']);

    // Delete all bookings for the selected date
    $deleteSql = "DELETE FROM equipment_booking WHERE booking_date = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("s", $deleteDate);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the same page to refresh
    header("Location: manageEquipmentBooking.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Equipment Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/navigation.css">
    <link rel="stylesheet" href="../css/homepage.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../script/officerNavBar.js" defer></script>
    <style>
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

        .add-booking {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .add-booking a {
            background-color: #1D5748;
            color: #F5F0DD;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
        }

        .add-booking a:hover {
            background-color: #014520;
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

        .table thead th {
            background-color: #1D5748 !important; /* Dark green */
            color: #FFFFFF !important; /* White font */
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

        /* Icon alignment */
        .fas {
            font-size: 16px; /* Adjust size for better visibility */
            line-height: 20px; /* Centers icon vertically */
        }

        .add-button-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .add-button {
            background-color: #1D5748;
            color: #FFFFFF;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            border: none;
        }

        .add-button:hover {
            background-color: #014520;
        }
    </style>
</head>

<body>
    <!-- Navigation bar -->
    <div id="navbar"></div>

    <div class="container my-5">

        <!-- Search Bar Section -->
        <div class="search-filter">
            <input style="width: 70%;" type="text" placeholder="Search Booking">
            <button class="filter-button" style="width: 20%;">Search</button>
        </div>

        <!-- Add Equipment Button -->
        <div class="add-button-container">
            <a href="userAddEquipment.php" class="add-button">Equipment Booking</a>
        </div>

        <!-- Booking Table -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Number</th>
                        <th>Booking Date</th>
                        <th>Equipment Booked</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // SQL to fetch booking dates and corresponding equipment names
                    $sql = "
                        SELECT eb.booking_date, GROUP_CONCAT(e.equipment SEPARATOR ', ') AS equipment_names 
                        FROM equipment_booking eb 
                        JOIN equipment e ON eb.equipment_id = e.id 
                        GROUP BY eb.booking_date 
                        ORDER BY eb.booking_date
                    ";

                    $result = $conn->query($sql);
                    $counter = 1;

                    if ($result && $result->num_rows > 0):
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <!-- Row Number -->
                                <td><?= $counter++ ?></td>

                                <!-- Booking Date -->
                                <td><?= htmlspecialchars($row['booking_date']) ?></td>

                                <!-- Equipment Booked -->
                                <td><?= htmlspecialchars($row['equipment_names']) ?></td>

                                <!-- Actions -->
                                <td>
                                    <!-- Edit Icon -->
                                    <a href="userEditEquipment.php?date=<?= urlencode($row['booking_date']) ?>" class='btn btn-primary btn-sm me-2'>Edit
                                    </a>

                                    <!-- Delete Icon -->
                                    <a href="userDeleteEquipment.php?date=<?= urlencode($row['booking_date']) ?>" class='btn btn-danger btn-sm'>Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr>
                            <td colspan="4" class="text-center">No bookings available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
