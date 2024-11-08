<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$vehicleId = $_GET['vehicleId'];
$sql = "SELECT * FROM ambulance WHERE vehicleId = '$vehicleId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "No such ambulance found!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $capacity = $_POST['capacity'];
    $availability = $_POST['availability'];

    $updateSql = "UPDATE ambulance SET type='$type', capacity='$capacity', availability='$availability' WHERE vehicleId='$vehicleId'";
    
    if ($conn->query($updateSql) === TRUE) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Success!',
                    text: 'Ambulance updated successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'manageAmbulance.php';
                    }
                });
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Error updating ambulance record: " . $conn->error . "',
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
    <title>Edit Ambulance</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/navigation.css">    
    <link rel="stylesheet" href="../css/manageAmbulance.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <a href="./manageAmbulance.php" class="btn btn-secondary mb-3">Back to Home</a>
        <br>
        <h1>Edit Ambulance Details</h1>

        <form action="" method="POST">
            <div class="form-group ambulance-input">
                <label for="vehicleId">Vehicle ID: </label>
                <input type="text" id="vehicleId" name="vehicleId" value="<?php echo $row['vehicleId']; ?>" readonly>
            </div>

            <div class="form-group ambulance-input">
                <label for="type">Type</label>
                <select class="form-select" id="type" name="type">
                    <option value="Basic Life Support" <?php if($row['type'] == 'Basic Life Support') echo 'selected'; ?>>Basic Life Support</option>
                    <option value="Advanced Life Support" <?php if($row['type'] == 'Advanced Life Support') echo 'selected'; ?>>Advanced Life Support</option>
                    <option value="Critical Care" <?php if($row['type'] == 'Critical Care') echo 'selected'; ?>>Critical Care</option>
                </select>
            </div>

            <div class="form-group ambulance-input">
                <label for="capacity">Capacity: </label>
                <input type="number" id="capacity" name="capacity" value="<?php echo $row['capacity']; ?>" required>
            </div>

            <div class="form-group ambulance-input">
                <label for="availability">Availability: </label>
                <select class="form-select" id="availability" name="availability">
                    <option value="Available" <?php if($row['availability'] == 'Available') echo 'selected'; ?>>Available</option>
                    <option value="Unavailable" <?php if($row['availability'] == 'Unavailable') echo 'selected'; ?>>Unavailable</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Ambulance</button>
        </form>
    </div>
</body>

</html>
