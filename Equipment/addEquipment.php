<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $equipment = $_POST['equipment'];
    $quantity = $_POST['quantity'];

        //Insert the new equipment entry into the database
        $sql = "INSERT INTO equipment (type, equipment, quantity) VALUES ('$type', '$equipment', '$quantity')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Equipment added successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'equipmmentList.php';
                        }
                    });
                });
            </script>";
        } 
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Error adding new equipment record: " . $conn->error . "',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
    }

?>

<!DOCTYPE html>
<html>

<head>
    <title>Equipment Manage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/navigation.css">    
    <link rel="stylesheet" href="../css/manageEquipment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <div class="container" style="width: auto; ">

        <a href="equipmmentList.php" style="text-decoration:none; color: black;"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>Back</a>
        <br>
        <h1 style="text-align: center;">Add New Equipment</h1>

            <div class="equipment-table justify-content-center">
                <form id="addEquipmentForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data"> 
                <div class="form-group equipment-input">
                        <label for="type">Type</label>
                        <div class="custom-select-wrapper">
                            <select class="form-select custom-select" aria-label="Default select example" id="type"
                                name="type" required>
                                <option value="" disabled selected>Select a type</option>
                                <option value="Dressing">Dressing</option>
                                <option value="Transportation">Transportation</option>
                                <option value="Splinting">Splinting</option>
                            </select>
                        </div>
                    </div>    
                <div class="form-group equipment-input">
                        <label for="equipment" class="form-label">Equipment name: </label>
                        <input type="text" id="equipment" name="equipment" required>
                    </div>

                    <div class="form-group equipment-input">
                        <label for="quantity" class="form-label">Quantity: </label>
                        <input type="quantity" id="quantity" name="quantity" required>
                    </div>

                    <button type="submit">Add Equipment</button>

                </form>
            </div>
        </div>

    <?php
    $conn->close();
    ?>

</body>

</html>