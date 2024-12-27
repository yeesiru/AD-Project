<!DOCTYPE html>
<html>

<head>
    <title>User Manage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/navigation.css">    
    <link rel="stylesheet" href="../css/addUser.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        *{
            font-size:15px;
        }

        span{
            color:red;
        }
    </style>
</head>

<body>

    <?php
    include("../database/db_conn.php");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_POST['userID'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $school = $_POST['school'];
        $role = $_POST['role'];

        $imagePath = NULL;
        if (isset($_FILES["image"]) && $_FILES["image"]["tmp_name"] != "") {
            $target_dir = __DIR__ . "/uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            $upload_dir = "../uploads/"; 
            $target_file = $upload_dir . basename($_FILES["image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        <strong>Error!</strong> File is not an image.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                $uploadOk = 0;
            }
        
            if ($_FILES["image"]["size"] > 5000000) {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        <strong>Error!</strong> Sorry, your file is too large.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                $uploadOk = 0;
            }
        
            if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        <strong>Error!</strong> Sorry, only JPG, JPEG, PNG & GIF files are allowed.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                $uploadOk = 0;
            }
        
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], __DIR__ . "/" . $target_file)) {
                    $imagePath = $target_file;
                } else {
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            <strong>Error!</strong> Sorry, there was an error uploading your file.
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                }
            }
        }

            //Insert the new user entry into the database
            try {
                $stmt = $conn->prepare("INSERT INTO user (userID, username, password, name, gender, email, phone, school, role, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssss", $userID, $username, $password, $name, $gender, $email, $phone, $school, $role, $imagePath);
        
                if ($stmt->execute()) {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                title: 'Success!',
                                text: 'User added successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'userManage.php';
                                }
                            });
                        });
                    </script>";
                }
                $stmt->close();
            } catch (mysqli_sql_exception $e) {
                // Detect duplicate entry or general error
                if ($e->getCode() == 1062) { // Duplicate entry error
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                title: 'Duplicate Entry!',
                                text: 'The User ID already exists. Please use a different User ID.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                    </script>";
                } else {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An unexpected error occurred: " . addslashes($e->getMessage()) . "',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                    </script>";
                }
            }
        }

    ?>

    <div class="container" style="width: auto; ">

        <a href="userManage.php" style="text-decoration:none; color: black;"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>Back</a>
        <br>
        <h1 style="text-align: center;">Create Account</h1>

            <div class="user-table justify-content-center">
                <form id="addUserForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data"> 
                    
                    <div class="form-group user-input">
                        <label for="userID" class="form-label"><span>*</span> User ID: </label>
                        <input type="text" id="userID" name="userID" placeholder="A001" required>
                        <small id="userIDError" style="color: red; display: none;">User ID must start with 'A' or 'F' followed by 3 digits (e.g., A001, F123).</small>
                    </div>

                    <div class="form-group user-input">
                        <label for="username" class="form-label"><span>*</span> Username: </label>
                        <input type="text" id="username" name="username" required>
                    </div>

                    <div class="form-group user-input">
                        <label for="password" class="form-label"><span>*</span> Password: </label>
                        <input type="password" id="password" name="password" required>
                        <span style="color:grey">At least 8 characters with 1 uppercase, 1 lowercase and number</span>
                        <small id="passwordError" style="color: red; display: none;">Password must be at least 8 characters long and include at least 1 uppercase letter, 1 lowercase letter, and 1 number.</small>
                    </div>

                    <div class="form-group user-input">
                        <label for="name"><span>*</span> Name: </label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="gender"><span>*</span> Gender: </label>
                        <input type="radio" name="gender" <?php if (isset($gender) && $gender=="female" ) echo "checked";?> value="female">Female
                        <input type="radio" name="gender" <?php if (isset($gender) && $gender=="male" ) echo "checked";?> value="male">Male
                    </div>

                    <div class="form-group user-input">
                        <label for="email"><span>*</span> Email: </label>
                        <input type="email" id="email" name="email" placeholder="xxx@example.com" required></textarea>
                    </div>

                    <div class="form-group user-input">
                        <label for="phone"><span>*</span> Phone Number:</label>
                        <input type="text" id="phone" name="phone" placeholder="0123456789" required>
                    </div>
                    <div class="form-group user-input">
                        <label for="school"><span>*</span> Responsible school:</label>
                        <input type="school" id="school" name="school"  placeholder="SMK example" required></textarea>
                    </div>

                    <div class="form-group user-input">
                        <label for="role"><span>*</span> Role</label>
                        <div class="custom-select-wrapper">
                            <select class="form-select custom-select" aria-label="Default select example" id="role"
                                name="role" required>
                                <option value="" disabled selected>Select a role</option>
                                <option value="admin">Admin</option>
                                <option value="officer">School Officer</option>
                            </select>
                        </div>
                        <small id="roleError" style="color: red; display: none;">User ID must match the selected role: 'A' for Admin, 'F' for Officer.</small>
                    </div>

                    <div class="mb-3">
                    <label for="image" class="form-label">Image:</label>
                    <input type="file" id="image" name="image" class="form-control">
                </div>

                    <button type="submit">Add User</button>

                </form>
            </div>
        </div>

        <script>
            document.getElementById("addUserForm").addEventListener("submit", function(event) {
                const userID = document.getElementById("userID").value;
                const password = document.getElementById("password").value;
                const role = document.getElementById("role").value;
                const userIDPattern = /^[AF]\d{3}$/;
                const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

                let isValid = true;

                // Reset error messages
                document.getElementById("userIDError").style.display = "none";
                document.getElementById("passwordError").style.display = "none";

                // Validate userID
                if (!userIDPattern.test(userID)) {
                    isValid = false;
                    document.getElementById("userIDError").style.display = "block";
                }

                // Validate password
                if (!passwordPattern.test(password)) {
                    isValid = false;
                    document.getElementById("passwordError").style.display = "block";
                }

                // Validate userID matches role
                if ((role === "admin" && !userID.startsWith("A")) || (role === "officer" && !userID.startsWith("F"))) {
                    isValid = false;
                    document.getElementById("roleError").style.display = "block";
                } else {
                    document.getElementById("roleError").style.display = "none";
                }

                // Prevent submission if invalid
                if (!isValid) {
                    event.preventDefault();
                }
            });
        </script>


    <?php
    $conn->close();
    ?>

</body>
</html>