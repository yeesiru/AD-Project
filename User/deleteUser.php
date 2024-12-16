
<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<?php
include("../database/db_conn.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userID = $_GET['userID'];
$stmt = $conn->prepare("DELETE FROM User WHERE userID = ?");
$stmt->bind_param("s", $userID);

if ($stmt->execute()) {
    // Redirect on successful deletion
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Success!',
                text: 'User deleted successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'userManage.php';
            }
            });
        });
      </script>";
} else {
    // Redirect on failure
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Failed to delete user record',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'userManage.php';
            }
        });
      </script>";
}
$stmt->close();

// $sql = "DELETE FROM User WHERE userID = $userID";
//     if ($conn->query($sql) === TRUE) {
//         echo "<script>
//         document.addEventListener('DOMContentLoaded', function() {
//             Swal.fire({
//                 title: 'Success!',
//                 text: 'User deleted successfully.',
//                 icon: 'success',
//                 confirmButtonText: 'OK'
//             });
//         });
//       </script>";
//     } else {
//         echo "<script>
//         Swal.fire({
//             title: 'Error!',
//             text: 'Error delete user record: " . $conn->error . "',
//             icon: 'error',
//             confirmButtonText: 'OK'
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 window.location.href = 'userManage.php';
//             }
//         });
//       </script>";
//     }

$conn->close();

?>

<body>
</body>
</html>