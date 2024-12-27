<!DOCTYPE html>
<html>

<head>
    <title>Profile</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/navigation.css">
    <link rel="stylesheet" href="../css/homepage.css">
    <script src="../script/adminNavBar.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/editProfile.css">


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
$stmt->bind_param("s", $userID);
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
        $name = $conn->real_escape_string($_POST['name']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $school = $conn->real_escape_string($_POST['school']);
        $image = $userData['image'];


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
    $updateSql = "UPDATE User SET name = ?, gender = ?, email = ?, phone = ?, school = ?, image = ? WHERE userID = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sssssss", $name, $gender, $email, $phone, $school, $image, $userID);
    
        try {
            if ($updateStmt->execute()) {
                echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Profile updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'ownProfile.php';
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

    <!-- Logo -->
    <div class="top">
        <div class="container">
            <div class="logo">
                <img src="../img/SJAM-logo.png" />
                <p>SJAM Connect</p>
            </div>
        </div>
    </div>

    <!-- Navigation bar -->
    <nav>
        <a href="##" onClick="history.go(-1); return false;"> &#11207; Back</a>
    </nav>

    <div class="container">
        <div class="justify-content-center row">
            <div class="form-back">
                <div class="form-container">
                    <form method="POST" action=" " class="mb-4 row" enctype="multipart/form-data">
                        <div class="col-lg-6">
                            <div class="mb-3 form-group user-input">
                                <label for="userID" class="form-label">User ID: </label>
                                <input style="opacity:0.5;" type="text" id="userID" name="userID" class="form-control"
                                    value="<?php echo htmlspecialchars($userData['userID']); ?>" readonly>
                            </div>

                            <div class="mb-3 form-group user-input">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($userData['name']); ?>" required>
                            </div>

                            <div class="mb-3 form-group user-input">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                            </div>

                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3 form-group user-input">
                                <label for="username" class="form-label">Username:</label>
                                <input style="opacity:0.5;" type="text" id="username" name="username"
                                    class="form-control" value="<?php echo htmlspecialchars($userData['username']); ?>" readonly>
                            </div>

                            <div class="mb-3 form-group">
                                <label for="gender">Gender: </label>
                                <div style="height: 40px; padding: 12px 15px;">
                                <input type="radio" name="gender" value="female" <?php if ($userData['gender']=='female') {echo 'checked';}?>>Female
                                <input type="radio" name="gender" value="male" <?php if ($userData['gender']=='male') {echo 'checked';}?>>Male
                                </div>
                            </div>

                            <div class="mb-3 form-group user-input">
                                <label for="phone" class="form-label">Phone Number:</label>
                                <input type="phone" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($userData['phone']); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3 form-group user-input">
                            <label for="school" class="form-label">Responsible School:</label>
                            <input type="school" id="school" name="school" class="form-control" value="<?php echo htmlspecialchars($userData['school']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image:</label>
                            <div>
                                <img src="<?php echo htmlspecialchars($userData['image']); ?>" alt="Current Image" class="img-thumbnail mt-2"
                                    style="height:15%; width:15%;">
                                <br>
                                <small class="form-text text-muted">Leave this field blank to keep the existing
                                    image.</small>
                                <input type="file" id="image" name="image" class="form-control" style="width: 30%;">
                            </div>
                        </div>

                        <button type="submit">Update profile</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>
    <?php
 
    $conn->close();
    ?>
</body>

</html>