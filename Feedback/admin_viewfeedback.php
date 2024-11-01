<!DOCTYPE html>
<html>
<head>
    <title>Admin Feedback Listing</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>
    <div class="container">
        <br>
        <div class="justify-content-between" style="display:flex;">
            <h2>Feedback Listings</h2>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Service Types</th>
                    <th>Rating</th>
                    <th>Message</th>
                    <th>Response</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch feedback from the database
                $sql = "SELECT * FROM feedback"; // Make sure the table name is correct
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['serviceType']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['rating']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['feedbackText']) . "</td>";
                        echo "<td>
                                <a href='?reply=" . $row['id'] . "' class='btn btn-primary btn-sm me-2'>Reply</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No feedback found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    $conn->close(); // Close the database connection after all operations
    ?>
</body>
</html>