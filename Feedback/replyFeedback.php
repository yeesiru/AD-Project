<?php
include("../database/db_conn.php"); // Include the database connection file

$message = "";

// Check if a feedback ID is provided
if (!isset($_GET['feedback_id'])) {
    die("No feedback selected for reply.");
}

$feedback_id = intval($_GET['feedback_id']);

// Fetch feedback details
$stmt = $conn->prepare("SELECT * FROM feedback WHERE id = ?");
$stmt->bind_param("i", $feedback_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Feedback not found.");
}

$feedback = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_response = $_POST['admin_response'];
    $feedback_id = intval($_POST['feedback_id']); // Retrieve feedback ID from the form

    // Use prepared statements to update the response in the database
    $stmt = $conn->prepare("UPDATE feedback SET admin_response = ?, status = 'responded' WHERE id = ?");
    $stmt->bind_param("si", $admin_response, $feedback_id);

    if ($stmt->execute()) {
        $message = "Success! Your response has been submitted.";
    } else {
        $message = "Error submitting response: " . $stmt->error;
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
    <link rel="stylesheet" href="../css/replyFeedback.css">

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
            window.location.href = "admin_viewfeedback.php"; // Redirect after alert
        });
    </script>
<?php endif; ?>

<div class="container" style="width: auto; ">

        <a href="admin_viewfeedback.php" style="text-decoration:none; color: black;"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>Back</a>
        <br>
        <h1 style="text-align: center;">Reply to feedback</h1>

    <!-- Response form -->
    <div id="responseForm">
    <h4>Feedback Details</h4>
        <p><strong>Service Type:</strong> <?php echo htmlspecialchars($feedback['serviceType']); ?></p>
        <p><strong>Rating:</strong> <?php echo htmlspecialchars($feedback['rating']); ?></p>
        <p><strong>Message:</strong> <?php echo htmlspecialchars($feedback['feedbackText']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($feedback['status']); ?></p>

        <form action="replyFeedback.php?feedback_id=<?php echo $feedback_id; ?>" method="POST">
        <input type="hidden" name="feedback_id" value="<?php echo $feedback_id; ?>">
        <label for="admin_response">Response:</label>
        <textarea name="admin_response" id="admin_response" rows="5" required></textarea>
        <button type="submit">Submit Response</button>
        </form>
    </div>
    
    <?php
    $conn->close();
    ?>

</body>

</html>