<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicleId = $_POST['vehicleId'];
    $type = $_POST['type'];
    $capacity = $_POST['capacity'];
    $availability = $_POST['availability'];

    // Insert the new ambulance entry into the database
    $sql = "INSERT INTO ambulance (vehicleId, type, capacity, availability) VALUES ('$vehicleId', '$type', '$capacity', '$availability')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Success!',
                    text: 'Ambulance added successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'viewAmbulance.php';
                    }
                });
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Error adding new ambulance record: " . $conn->error . "',
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
    <title>Ambulance Manage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/navigation.css">    
    <link rel="stylesheet" href="../css/manageAmbulance.css">
    <link rel="stylesheet" href="../css/styleAmbulance.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .container{
            background-color: #F5F0DD;
        }
        button[type="submit"] {
        background-color: #1D5748;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
        font-weight: bold;
        }
        
        button[type="submit"]:hover {
            background-color: #143D33;
        }
        
        .custom-select-wrapper {
            position: relative;
        }
    </style>
</head>

<body>
    <div class="container" style="width: auto;">
        <a href="./viewAmbulance.php" class="btn btn-secondary mb-3 btn-warning-custom">Back</a>
        <br>
        <h1 style="text-align: center;">Add New Ambulance</h1>

        <div class="ambulance-table justify-content-center">
            <form id="addAmbulanceForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">
                
                <div class="form-group ambulance-input">
                    <label for="vehicleId" class="form-label">Vehicle ID<span class="text-danger">*</span></label>
                    <input type="text" id="vehicleId" name="vehicleId" pattern="[A-Za-z]{1}\d{3}" title="Vehicle ID must be one letter followed by three digits (e.g., S123)" required>
                </div>

                <div class="form-group ambulance-input">
                    <label for="type">Type<span class="text-danger">*</span></label>
                    <div class="custom-select-wrapper">
                        <select class="form-select custom-select" aria-label="Default select example" id="type"
                            name="type" required>
                            <option value="" disabled selected>Select a type</option>
                            <option value="Basic Life Support">Basic Life Support</option>
                            <option value="Advanced Life Support">Advanced Life Support</option>
                            <option value="Critical Care">Critical Care</option>
                        </select>
                    </div>
                </div>    

                <div class="form-group ambulance-input">
                    <label for="capacity" class="form-label">Capacity<span class="text-danger">*</span></label>
                    <input type="number" id="capacity" name="capacity" min="1" max="12" required>
                </div>

                <div class="form-group ambulance-input">
                    <label for="availability" class="form-label">Availability<span class="text-danger">*</span></label>
                    <select class="form-select" id="availability" name="availability" required>
                        <option value="" disabled selected>Select availability</option>
                        <option value="Available">Available</option>
                        <option value="Unavailable">Unavailable</option>
                    </select>
                </div>

                <button type="submit">Add Ambulance</button>
            </form>
        </div>
    </div>

    <?php
    $conn->close();
    ?>
</body>

</html>
