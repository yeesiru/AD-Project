<?php
include("../database/db_conn.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedDate = $conn->real_escape_string($_POST['date']);
    $bookingDetails = $_POST['booking'];

    $errors = [];
    $success = [];

    foreach ($bookingDetails as $equipmentId => $quantity) {
        $equipmentId = intval($equipmentId);
        $quantity = intval($quantity);

        // Skip if the quantity is 0
        if ($quantity == 0) {
            continue;
        }

        // Fetch current equipment quantity
        $availabilityCheck = "SELECT quantity FROM equipment WHERE id = $equipmentId";
        $availabilityResult = $conn->query($availabilityCheck);
        $row = $availabilityResult->fetch_assoc();

        if ($row) {
            $availableQuantity = $row['quantity'];

            // Check availability
            if ($quantity > $availableQuantity) {
                $errors[] = "Requested quantity for equipment ID $equipmentId exceeds availability.";
            } else {
                // Reduce available quantity
                $newQuantity = $availableQuantity - $quantity;
                $updateQuery = "UPDATE equipment SET quantity = $newQuantity WHERE id = $equipmentId";

                if ($conn->query($updateQuery)) {
                    // Insert into equipment_booking table
                    $userId = 1; // Temporary hardcoded user ID for testing
                    $logBooking = "INSERT INTO equipment_booking (equipment_id, user_id, quantity, booking_date) 
                                   VALUES ($equipmentId, $userId, $quantity, '$selectedDate')";

                    if ($conn->query($logBooking)) {
                        $success[] = "Equipment ID $equipmentId booked successfully.";
                    } else {
                        $errors[] = "Error booking equipment ID $equipmentId: " . $conn->error;
                    }
                } else {
                    $errors[] = "Error updating quantity for equipment ID $equipmentId: " . $conn->error;
                }
            }
        } else {
            $errors[] = "Equipment ID $equipmentId updated successfully.";
        }
    }

    // Display success or error messages
    if (!empty($success)) {
        echo "<script>alert('" . implode("\\n", $success) . "');</script>";
    }
    if (!empty($errors)) {
        echo "<script>alert('" . implode("\\n", $errors) . "');</script>";
    }
}

// Fetch all bookings
$fetchBookingsQuery = "SELECT * FROM equipment_booking";
$bookingResults = $conn->query($fetchBookingsQuery);

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
            justify-content: center;
            margin-bottom: 20px;
            width: 100%;
        }

        .search-form {
            display: flex;
            justify-content: center;
            gap: 15px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .search-form input {
            flex: 1;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .search-form button {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #1D5748;
            color: #fff;
        }

        .search-form button:hover {
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
            <form class="search-form" method="GET" action="">
                <input style="width: 70%;" type="text" name="search_date" placeholder="Search by Date (YYYY-MM-DD)">
                <button type="submit" style="width: 20%;" class="filter-button" >Search</button>
            </form>
        </div>

        <!-- Add Equipment Button -->
        <div class="add-button-container">
            <a href="userAddEquipment.php" class="add-button">Book Equipment</a>
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
                    // Fetch search date from query parameters
                    $search_date = isset($_GET['search_date']) ? $_GET['search_date'] : '';

                    // SQL to fetch booking dates and corresponding equipment names
                    $sql = "
                        SELECT eb.booking_date, GROUP_CONCAT(e.equipment SEPARATOR ', ') AS equipment_names 
                        FROM equipment_booking eb 
                        JOIN equipment e ON eb.equipment_id = e.id 
                    ";

                    // Add filter condition if search date is provided
                    if (!empty($search_date)) {
                        $sql .= " WHERE eb.booking_date = ? ";
                    }

                    $sql .= " GROUP BY eb.booking_date ORDER BY eb.booking_date ";

                    $stmt = $conn->prepare($sql);

                    if (!empty($search_date)) {
                        $stmt->bind_param('s', $search_date);
                    }

                    $stmt->execute();
                    $result = $stmt->get_result();

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

                    <?php
                    // Close statement
                    $stmt->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

