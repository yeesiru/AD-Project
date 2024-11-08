<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_GET['id'];
$sql = "SELECT * FROM User WHERE id = $userId";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $school = $_POST['school'];
    $role = $_POST['role'];
    
    

    // Update the user listing in the database
    $sql = "UPDATE User SET username = '$username', password = '$password', name = '$name', gender = '$gender', email = '$email', phone = '$phone', school = '$school', role = '$role' WHERE id = $userId";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Success!',
                        text: 'User Entry updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '#';
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
    <title>Edit Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/manageUser.css">

</head>

<body>
    <div class="container" style="width: 50vw;">
        <a href="userManage.html" style="text-decoration:none; color: black;"><svg xmlns="http://www.w3.org/2000/svg"
                height="24px" viewBox="0 -960 960 960" width="24px" fill="black">
                <path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z" />
            </svg>Back</a>
        <br>
        <h1 class="my-4" style="text-align: center;">Edit User</h1>

        <!-- Form to edit the user entry -->
        <div class="user-table justify-content-center">
            <form method="POST" action=" " class="mb-4" enctype="multipart/form-data">

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
                    <select class="form-select mb-3" name="role" aria-label="Default select example" id="role"
                        name="role" required>
                        <option value="admin">Admin</option>
                        <option value="officer">School Officer</option>
                    </select>
                </div>

                <button type="submit">Update user</button>
            </form>
        </div>
    </div>


    <!-- <?php
 
    $conn->close();
    ?> -->
</body>

</html>