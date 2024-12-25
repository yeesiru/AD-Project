<!DOCTYPE html>
<html>

<head>
    <title>Edit Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/addUser.css">

</head>

<body>
<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user ID securely
$userID = $_GET['userID'];
$sql = "SELECT * FROM User WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userID);  // Use 's' for string type
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize and fetch inputs
        $userID = $conn->real_escape_string($_POST['userID']);
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);
        $name = $conn->real_escape_string($_POST['name']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $school = $conn->real_escape_string($_POST['school']);
        $role = $conn->real_escape_string($_POST['role']);
        $image = $userData['image'];

         // Only check for duplicates if the username or userID is modified
        if ($username != $userData['username'] || $userID != $userData['userID']) {
            $checkSql = "SELECT * FROM User WHERE username = ? OR userID = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("ss", $username, $userID);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                echo "<script>
                    Swal.fire({
                        title: 'Duplicate Entry!',
                        text: 'The username or userID already exists. Please use unique values.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                </script>";
            }
        }

    $target_dir = __DIR__ . "/uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    $upload_dir = "uploads/"; 
    $target_file = $upload_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    
    if (!empty($_FILES["image"]["name"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        if ($_FILES["image"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }


        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }


        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], __DIR__ . "/" . $target_file)) {
                $image = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } 

    // Update user in the database
    $updateSql = "UPDATE User SET username = ?, password = ?, name = ?, gender = ?, email = ?, phone = ?, school = ?, role = ?, image = ? WHERE userID = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssssssssss", $username, $password, $name, $gender, $email, $phone, $school, $role, $image, $userID);
    
        try {
            if ($updateStmt->execute()) {
                echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'User updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'userManage.php';
                    });
                </script>";
            }
        } catch (Exception $e) {
                echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'An unexpected error occurred: " . addslashes($e->getMessage()) . "',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                </script>";
            }
        }
?>

    <div class="container" style="width: 50vw;">
        <a href="userManage.php" style="text-decoration:none; color: black;"><svg xmlns="http://www.w3.org/2000/svg"
                height="24px" viewBox="0 -960 960 960" width="24px" fill="black">
                <path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z" />
            </svg>Back</a>
        <br>
        <h1 class="my-4" style="text-align: center;">Edit User</h1>

        <!-- Form to edit the user entry -->
        <div class="user-table justify-content-center">
            <form method="POST" action=" " class="mb-4" enctype="multipart/form-data">

                <div class="form-group user-input">
                        <label for="userID" class="form-label">User ID: </label>
                        <input style="opacity:0.5;" type="text" id="userID" name="userID" class="form-control" value="<?php echo $userData['userID']; ?>" readonly>
                </div>

                <div class="mb-3 form-group user-input">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo $userData['username']; ?>" required>
                </div>

                <div class="mb-3 form-group user-input">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" value="<?php echo $userData['password']; ?>" required>
                </div>

                <div class="mb-3 form-group user-input">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo $userData['name']; ?>" required>
                    
                </div>

                <div class="mb-3 form-group">
                    <label for="gender">Gender: </label>
                    <input type="radio" name="gender" value="female" <?php if ($userData['gender']=='female') {echo 'checked';}?>>Female
                    <input type="radio" name="gender" value="male" <?php if ($userData['gender']=='male') {echo 'checked';}?>>Male
                </div>

                <div class="mb-3 form-group user-input">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $userData['email']; ?>" required>
                </div>

                <div class="mb-3 form-group user-input">
                    <label for="phone" class="form-label">Phone Number:</label>
                    <input type="phone" id="phone" name="phone" class="form-control" value="<?php echo $userData['phone']; ?>" required>
                </div>

                <div class="mb-3 form-group user-input">
                    <label for="school" class="form-label">Responsible School:</label>
                    <input type="school" id="school" name="school" class="form-control" value="<?php echo $userData['school']; ?>" required>
                </div>


                <div class="mb-3 form-group user-input">
                    <label for="role" class="form-label">Role:</label>
                    <select class="form-select mb-3" name="role" id="role" required>
                        <option value="admin" <?php if ($userData['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                        <option value="officer" <?php if ($userData['role'] == 'officer') echo 'selected'; ?>>School Officer</option>
                    </select>
                </div>

                <div class="mb-3">
                <label for="image" class="form-label">Image:</label>
                <input type="file" id="image" name="image" class="form-control">
                <div class="row" style="padding-left:10px;">
                <small class="form-text text-muted">Leave this field blank to keep the existing image.</small>
                <img src="<?php echo $userData['image']; ?>" alt="Current Image" class="img-thumbnail mt-2" style="height:25%; width:25%;">
                </div>
            </div>

                <button type="submit">Update user</button>
            </form>
        </div>
    </div>


    <?php
 
    $conn->close();
    ?>
</body>

</html>