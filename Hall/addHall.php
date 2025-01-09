<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hall_id = $_POST['hall_id'];
    $name = $_POST['name'];
    $capacity = $_POST['capacity'];
    $location = $_POST['location'];
    $facility = $_POST['facility'];

    // Insert the new hall entry into the database
    $sql = "INSERT INTO hall (hall_id, name, capacity, location, facility) VALUES ('$hall_id', '$name', '$capacity', '$location', '$facility')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Success!',
                    text: 'Hall added successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'viewHallList.php';
                    }
                });
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Error adding new hall record: " . $conn->error . "',
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
    <title>Hall Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">   
    <link rel="stylesheet" href="../css/navigation.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
    <div class="modal-container">
        <div class="modal-form">
            <h1>Add Hall</h1>
            <form id="addHallForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                
                <div class="form-group hall-input">
                    <label for="hall_id" class="form-label">Hall ID<span class="text-danger">*</span></label>
                    <input type="text" id="hall_id" name="hall_id" placeholder="H432" required>
                </div>

                <div class="form-group hall-input">
                    <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" placeholder="Main Hall" required>
                </div>

                <div class="form-group hall-input">
                    <label for="capacity" class="form-label">Capacity<span class="text-danger">*</span></label>
                    <input type="number" id="capacity" name="capacity" placeholder="0"required min="1" max="500">
                </div>

                <div class="form-group hall-input">
                    <label for="location" class="form-label">Location<span class="text-danger">*</span></label>
                    <input type="text" id="location" name="location" placeholder="Level 2" required>
                </div>

                <div class="form-group hall-input">
                    <label for="facility" class="form-label">Facility<span class="text-danger">*</span></label>
                    <input type="text" id="facility" name="facility" placeholder="Audio Equipment" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Save</button>
                <a href="viewHallList.php" class="btn btn-secondary mt-2">Cancel</a>
            </form>
        </div>
    </div>

    <?php
    $conn->close();
    ?>
</body>

</html>
