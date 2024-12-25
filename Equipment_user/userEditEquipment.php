<?php
    include("../database/db_conn.php");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle GET or POST date
    if (isset($_GET['date'])) {
        $selectedDate = $conn->real_escape_string($_GET['date']);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $selectedDate = $conn->real_escape_string($_POST['date']);
    } else {
        $selectedDate = null;
    }

    // Fetch bookings if a date is provided
    if ($selectedDate) {
        $sql = "SELECT eb.id AS booking_id, e.type, e.equipment, eb.quantity, e.quantity AS available_quantity
                FROM equipment_booking eb
                JOIN equipment e ON eb.equipment_id = e.id
                WHERE eb.booking_date = '$selectedDate'";
        $result = $conn->query($sql);
    } else {
        $result = null;
    }
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Equipment Booking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F5F0DD;
            color: #1D5748;
        }

        h1 {
            color: #1D5748;
            margin-bottom: 20px;
        }

        .container {
            background-color: #FFF8EB;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 40px auto;
        }

        .table {
            background-color: #FFF8EB;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #1D5748;
            color: #FFF;
            text-align: center;
            padding: 10px;
        }

        .table td {
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
        }

        .table tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        .btn-primary {
            background-color: #1D5748;
            border: none;
            color: #F5F0DD;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #014520;
        }

        input[type="date"],
        input[type="number"] {
            border: 1px solid #ddd;
            padding: 8px;
            border-radius: 5px;
            width: 100%;
        }

        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        label {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center">Edit Equipment Booking</h1>

        <!-- Display Bookings -->
        <?php if ($selectedDate): ?>
            <h3 class="text-center">Bookings for <?= htmlspecialchars($selectedDate) ?></h3>
            <?php if ($result && $result->num_rows > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Equipment Name</th>
                            <th>Booked Quantity</th>
                            <th>Available Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['type']) ?></td>
                                <td><?= htmlspecialchars($row['equipment']) ?></td>
                                <td><?= intval($row['quantity']) ?></td>
                                <td><?= intval($row['available_quantity']) ?></td>
                                <td>
                                    <form method="POST" action="manageEquipmentBooking.php">
                                        <input type="hidden" name="booking_id" value="<?= intval($row['booking_id']) ?>">
                                        <input type="hidden" name="date" value="<?= htmlspecialchars($selectedDate) ?>">
                                        <input type="number" name="quantity" min="1" max="<?= intval($row['available_quantity']) ?>" value="<?= intval($row['quantity']) ?>" required>
                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center text-danger">No bookings found for the selected date.</p>
            <?php endif; ?>
        <?php else: ?>
            <!-- Date Selection -->
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="date" class="form-label">Select Booking Date:</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="d-flex justify-content-center mb-4">
                    <button type="submit" class="btn btn-primary">View Bookings</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>
