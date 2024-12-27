<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'id' parameter is in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $equipmentId = $_GET['id'];
} else {
    echo "Invalid equipment ID.";
    exit;
}

// Fetch the equipment details
$sql = "SELECT * FROM equipment WHERE id = $equipmentId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $equipmentData = $result->fetch_assoc();
} else {
    echo "Equipment not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $equipment = $_POST['equipment'];
    $quantity = $_POST['quantity'];

    // Update the equipment entry in the database
    $sql = "UPDATE equipment SET type = '$type', equipment = '$equipment', quantity = '$quantity' WHERE id = $equipmentId";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Equipment updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'equipmmentList.php';
                        }
                    });
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Error updating record: " . $conn->error . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
              </script>";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Equipment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/manageEquipment.css">
</head>

<body>
    <div class="container" style="width: 50vw;">
        <a href="equipmmentList.php" style="text-decoration:none; color: black;">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black">
                <path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/>
            </svg>Back
        </a>
        <br>
        <h1 class="my-4" style="text-align: center;">Edit Equipment</h1>

        <!-- Form to edit the equipment entry -->
        <div class="equipment-table justify-content-center">
            <form method="POST" action="" class="mb-4">

                <div class="mb-3 form-group equipment-input">
                    <label for="type" class="form-label">Type:</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="dressing" <?php if ($equipmentData['type'] == 'dressing') echo 'selected'; ?>>Dressing</option>
                        <option value="transportation" <?php if ($equipmentData['type'] == 'transportation') echo 'selected'; ?>>Transportation</option>
                        <option value="splinting" <?php if ($equipmentData['type'] == 'splinting') echo 'selected'; ?>>Splinting</option>
                    </select>
                </div>

                <div class="mb-3 form-group equipment-input">
                    <label for="equipment" class="form-label">Equipment name:</label>
                    <input type="text" id="equipment" name="equipment" class="form-control" value="<?php echo $equipmentData['equipment']; ?>" required>
                </div>

                <div class="mb-3 form-group equipment-input">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" value="<?php echo $equipmentData['quantity']; ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Update Equipment</button>
            </form>
        </div>
    </div>

    <?php
    $conn->close();
    ?>
</body>

</html>
