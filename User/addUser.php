<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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

        //Insert the new user entry into the database
        $sql = "INSERT INTO user (username, password, name, gender, email, phone, school, role) VALUES ('$username', '$password', '$name','$gender', '$email', '$phone', '$school', '$role')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Success!',
                        text: 'User entry added successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'userManage.html';
                        }
                    });
                });
            </script>";
        } 
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Error adding new user record: " . $conn->error . "',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
    }

?>

<!DOCTYPE html>
<html>

<head>
    <title>User Manage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/navigation.css">    
    <link rel="stylesheet" href="../css/manageUser.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <div class="container" style="width: auto; ">

        <a href="userManage.html" style="text-decoration:none; color: black;"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>Back</a>
        <br>
        <h1 style="text-align: center;">Create Account</h1>

            <div class="user-table justify-content-center">
                <form id="addUserForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data"> 
                    <div class="form-group user-input">
                        <label for="username" class="form-label">Username: </label>
                        <input type="text" id="username" name="username" required>
                    </div>

                    <div class="form-group user-input">
                        <label for="password" class="form-label">Password: </label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group user-input">
                        <label for="name">Name: </label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender: </label>
                        <input type="radio" name="gender" <?php if (isset($gender) && $gender=="female" ) echo "checked";?> value="female">Female
                        <input type="radio" name="gender" <?php if (isset($gender) && $gender=="male" ) echo "checked";?> value="male">Male
                    </div>

                    <div class="form-group user-input">
                        <label for="email">Email: </label>
                        <input type="email" id="email" name="email" required></textarea>
                    </div>

                    <div class="form-group user-input">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone" required>
                    </div>
                    <div class="form-group user-input">
                        <label for="school">Responsible school:</label>
                        <input type="school" id="school" name="school" required></textarea>
                    </div>

                    <div class="form-group user-input">
                        <label for="role">Role</label>
                        <div class="custom-select-wrapper">
                            <select class="form-select custom-select" aria-label="Default select example" id="role"
                                name="role" required>
                                <option value="" disabled selected>Select a role</option>
                                <option value="admin">Admin</option>
                                <option value="officer">School Officer</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit">Add User</button>

                </form>
            </div>
        </div>

    <?php
    $conn->close();
    ?>

</body>

</html>