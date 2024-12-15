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

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .page{
            height: 100vh;
        }

        .container{
            padding: 20px;
            border-radius: 8px;
            width: 100%;
            margin: auto;
            box-sizing: border-box;
        }

        #feedbackForm {
            position: relative;
            border: 2px solid black;
            border-radius: 20px;
            background-color: #dbeae6;
            padding: 40px;
            width: 100%; 
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        select, input[type="number"], textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #41a072;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #3d9366;
        }

        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: left; /* Centers the stars */
            margin-top: 10px;
        }
        .rating input {
            display: none;
        }
        .rating label {
            font-size: 2rem;
            color: #fff;
            cursor: pointer;
            padding: 0 0.2rem;
            transition: transform 0.2s ease, color 0.2s ease;
        }
        .rating input:checked ~ label,
        .rating label:hover,
        .rating label:hover ~ label  {
            transform: scale(1.1);
            color: #FFD700;
        }

        h2 {
            color: #343a40;
            font-weight: bold;
        }

        @media (max-width: 768px) {
        #feedbackForm {
            margin-left: 10px;
            margin-right: 10px;
        }
    
    }
    </style>

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