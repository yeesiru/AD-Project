<?php
include("../database/db_conn.php"); // Include the database connection file

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceType = $_POST['serviceType'];
    $rating = $_POST['rating'];
    $feedbackText = $_POST['feedbackText'];

    // Use prepared statements for secure insertion
    $stmt = $conn->prepare("INSERT INTO feedback (serviceType, rating, feedbackText, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("sis", $serviceType, $rating, $feedbackText);

    if ($stmt->execute()) {
        $message = "Success! Your feedback has been submitted.";
    } else {
        $message = "Error submitting feedback: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Feedback Page</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/addFeedback.css">
</head>

<body>
<?php if ($message): ?>
    <script>
        Swal.fire({
            title: '<?php echo strpos($message, "Error") === false ? "Success!" : "Error!"; ?>',
            text: '<?php echo $message; ?>',
            icon: '<?php echo strpos($message, "Error") === false ? "success" : "error"; ?>',
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = "officer-feedback.php"; // Redirect after alert
        });
    </script>
<?php endif; ?>

<div class="container" style="width: auto; ">

        <a href="officer-feedback.php" style="text-decoration:none; color: black;"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>Back</a>
        <br>
        <h1 style="text-align: center;">Add Feedback</h1>

    <!-- Feedback form -->
    <div id="feedbackForm">
        <form action="addFeedback.php" method="POST">
            <label for="serviceType">Service Type:</label>
            <select name="serviceType" id="serviceType" required>
                <option value="">Select Service</option>
                <option value="hall">Hall Condition</option>
                <option value="equipment">Equipment Functionality</option>
                <option value="ambulance">Ambulance Service</option>
            </select>

            <label for="rating">Rating:</label>
            <div class="rating">
                <input type="radio" name="rating" id="star5" value="5"><label for="star5">★</label>
                <input type="radio" name="rating" id="star4" value="4"><label for="star4">★</label>
                <input type="radio" name="rating" id="star3" value="3"><label for="star3">★</label>
                <input type="radio" name="rating" id="star2" value="2"><label for="star2">★</label>
                <input type="radio" name="rating" id="star1" value="1"><label for="star1">★</label>
            </div>

            <label for="feedbackText">Feedback:</label>
            <textarea id="feedbackText" name="feedbackText" rows="5" required></textarea>

            <button type="submit">Submit Feedback</button>
        </form>
    </div>
    
    <?php
    $conn->close();
    ?>

</body>

</html>