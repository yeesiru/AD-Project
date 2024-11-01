<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize a variable to hold the success/error message
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceType = $_POST['serviceType'];
    $rating = $_POST['rating'];
    $feedbackText = $_POST['feedbackText'];

    // Insert the new user entry into the database
    $sql = "INSERT INTO feedback (serviceType, rating, feedbackText) VALUES ('$serviceType', '$rating', '$feedbackText')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Success! Feedback entry added successfully.";
    } else {
        $message = "Error adding new feedback record: " . $conn->error;
    }
}

if (isset($_GET['reply'])) {
    $userID = $_GET['reply'];
    header("Location:admin_respondfeedback.php?id=$userID");
    exit;
}
?>

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
        <a href="../admin-main.html" class="nav-icon">
            <i class="fas fa-home fa-2x"></i>
        </a>
        <div class="justify-content-between" style="display:flex;">
            <h2>Feedback Listings</h2>
        </div>

        <?php if ($message): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: '<?php echo strpos($message, 'Error') === false ? 'Success!' : 'Error!'; ?>',
                        text: '<?php echo $message; ?>',
                        icon: '<?php echo strpos($message, 'Error') === false ? 'success' : 'error'; ?>',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        <?php endif; ?>

    </div> 
    // Display entire user feedback here... including status of admin response


    <?php
    $conn->close(); // Close the database connection after all operations
    ?>
</body>
</html>
