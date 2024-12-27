<?php
// Ensure the userID is available (e.g., from session or GET/POST)
session_start();
if (!isset($_SESSION['userID'])) {
    die("User is not logged in.");
}
$userID = $_SESSION['userID'];

// Ensure the database connection is initialized
include("../database/db_conn.php");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

if (isset($_GET['edit'])) {
    $userID = $_GET['edit'];
    header("Location:editProfile.php?userID=$userID");
    exit;
}

if (isset($_GET['changePass'])) {
    $userID = $_GET['changePass'];
    header("Location:changePassword.php?userID=$userID");
    exit;
}

// Fetch the logged-in user's profile
$sql = "SELECT userID, username, name, role, gender, email, phone, school, image FROM User WHERE userID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$profile = mysqli_fetch_assoc($result);

// Fetch other users (limited info)
$sqlOtherUsers = "SELECT name, role, gender, email, phone, school, image FROM User WHERE userID != ?";
$stmtOtherUsers = mysqli_prepare($conn, $sqlOtherUsers);
mysqli_stmt_bind_param($stmtOtherUsers, "s", $userID);
mysqli_stmt_execute($stmtOtherUsers);
$otherUsers = mysqli_stmt_get_result($stmtOtherUsers);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Profile</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/navigation.css">
    <script src="../script/<?php echo $profile['role']; ?>NavBar.js" defer></script>
    <link rel="stylesheet" href="../css/ownProfile.css">

    <style>
        /* Center the modal */
        .modal-dialog {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh; /* Full viewport height for vertical centering */
        }

        /* Style the modal content to match the Create Account form */
        .modal-content {
            border-radius: 10px;
            border: 1px solid #ccc;
            background-color: #f3f9f7; /* Light greenish tone */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Style the form inside the modal */
        .modal-body form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-label {
            font-weight: bold;
        }

        .form-text {
            font-size: 0.9em;
            color: #6c757d;
        }

        .modal-footer {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>

    <!-- Navigation bar -->
    <div id="navbar"></div>

    <div class="container">
        <div class="title">
            | Personal Information
        </div>

        <div class="row">
            <div class="image" style="text-align: center;">
                <img src="<?php echo !empty($profile['image']) ? $profile['image'] : 'uploads/default.png'; ?>" alt="User Image" />
            </div>

            <div class="info">
                <div class="col-lg-6" style="display: flex;">
                    <div style="padding-right: 10px;">
                        <p><strong>User ID:</strong></p>
                        <p><strong>Username:</strong></p>
                        <p><strong>Name:</strong></p>
                        <p><strong>Role:</strong></p>
                    </div>

                    <div style="padding-right: 10px;">
                        <p><?php echo $profile['userID']; ?></p>
                        <p><?php echo $profile['username']; ?></p>
                        <p><?php echo $profile['name']; ?></p>
                        <p><?php echo $profile['role']; ?></p>
                    </div>
                </div>

                <div class="col-lg-6" style="display: flex;">
                    <div style="padding-right: 10px;">
                        <p><strong>Gender:</strong></p>
                        <p><strong>Email:</strong></p>
                        <p><strong>Phone Number:</strong></p>
                        <p><strong>Responsible school:</strong></p>
                    </div>

                    <div style="padding-right: 10px;">
                        <p><?php echo $profile['gender']; ?></p>
                        <p><?php echo $profile['email']; ?></p>
                        <p><?php echo $profile['phone']; ?></p>
                        <p><?php echo $profile['school']; ?></p>
                    </div>
                </div>
            </div>

            <div class="btn-container">
                <?php echo"<a href='?edit=" . $profile['userID'] . "' class='btn btn-primary'>Edit</a>"?>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                    Change Password
                </button>            
            </div>

            <!-- Change Password Modal -->
            <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="changePasswordForm">
                                <div class="mb-3">
                                    <label for="currentPassword" class="form-label">Current Password</label>
                                    <input type="password" id="currentPassword" name="currentPassword" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">New Password</label>
                                    <input type="password" id="newPassword" name="newPassword" class="form-control" required>
                                    <small class="form-text text-muted">At least 8 characters, with 1 uppercase, 1 lowercase, and 1 number.</small>
                                </div>
                                <div class="mb-3">
                                    <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
                                    <input type="password" id="confirmNewPassword" name="confirmNewPassword" class="form-control" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="savePasswordButton" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("savePasswordButton").addEventListener("click", function() {
            const currentPassword = document.getElementById("currentPassword").value;
            const newPassword = document.getElementById("newPassword").value;
            const confirmNewPassword = document.getElementById("confirmNewPassword").value;

            // Password validation pattern
            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

            // Validate new password and confirmation
            if (!passwordPattern.test(newPassword)) {
                Swal.fire({
                    icon: "error",
                    title: "Invalid Password",
                    text: "New password must have at least 8 characters, including 1 uppercase letter, 1 lowercase letter, and 1 number.",
                });
                return;
            }

            if (newPassword !== confirmNewPassword) {
                Swal.fire({
                    icon: "error",
                    title: "Password Mismatch",
                    text: "New password and confirmation do not match.",
                });
                return;
            }

            // Show loading SweetAlert
            Swal.fire({
                title: "Updating Password...",
                text: "Please wait.",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            // Submit the password change using AJAX
            fetch("changePassword.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    currentPassword: currentPassword,
                    newPassword: newPassword
                })
            })
                .then(response => response.json())
                .then(data => {
                    Swal.close(); // Close the loading dialog
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Password Updated",
                            text: "Your password has been updated successfully.",
                        }).then(() => {
                            // Close the modal after success
                            const modal = bootstrap.Modal.getInstance(document.getElementById("changePasswordModal"));
                            modal.hide();
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Update Failed",
                            text: data.message || "Failed to update password. Please try again.",
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    Swal.fire({
                        icon: "error",
                        title: "Unexpected Error",
                        text: "An unexpected error occurred. Please try again later.",
                    });
                    console.error(error);
                });
        });
</script>


</body>

<?php
    $conn->close();
?>

</html>