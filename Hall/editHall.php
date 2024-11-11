<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$hall_id = $_GET['hall_id'];
$sql = "SELECT * FROM halls WHERE hall_id = '$hall_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "No such hall found!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $capacity = $_POST['capacity'];
    $location = $_POST['location'];
    $facility = $_POST['facility'];

    $updateSql = "UPDATE halls SET name='$name', capacity='$capacity', location='$location', facility='$facility' WHERE hall_id='$hall_id'";
    
    if ($conn->query($updateSql) === TRUE) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Success!',
                    text: 'Hall updated successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'manageHall.php';
                    }
                });
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Error updating hall record: " . $conn->error . "',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Hall</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/navigation.css">    
    <link rel="stylesheet" href="../css/manageHall.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Add the additional styles here */
        .modal-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-form {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .modal-form h1 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 0.5rem;
            display: block;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #006d47;
            border: none;
            color: #fff;
            font-weight: bold;
            text-align: center;
            border-radius: 5px;
        }

        .btn-secondary {
            background-color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="./manageHall.php" class="btn btn-secondary mb-3">Back to Home</a>
        <br>
        <h1>Edit Hall Details</h1>

        <form action="" method="POST">
            <div class="form-group hall-input">
                <label for="hall_id">Hall ID: </label>
                <input type="text" id="hall_id" name="hall_id" value="<?php echo $row['hall_id']; ?>" readonly>
            </div>

            <div class="form-group hall-input">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>" required>
            </div>

            <div class="form-group hall-input">
                <label for="capacity">Capacity: </label>
                <input type="number" id="capacity" name="capacity" value="<?php echo $row['capacity']; ?>" required min="1">
            </div>

            <div class="form-group hall-input">
                <label for="location">Location: </label>
                <input type="text" id="location" name="location" value="<?php echo $row['location']; ?>" required>
            </div>

            <div class="form-group hall-input">
                <label for="facility">Facility:</label>
                <input type="text" id="facility" name="facility" value="<?php echo $row['facility']; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Hall</button>
        </form>
    </div>
</body>

</html>
