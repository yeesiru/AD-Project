<?php
session_start();

include("../database/db_conn.php");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM User WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {

            $_SESSION["Login"] = "YES";
            $_SESSION['userID'] = $row['userID'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role'];

            switch ($row['role']) {
                case "admin":
                    header("Location: adminHomepage.html");
                    break;
                case "officer":
                    header("Location: officerHomepage.html");
                    break;
                default:
                header("Location: login.html?error=Unauthorized role");
            }
            exit();
        }
    }

    // Login failed
    $_SESSION["Login"] = "NO";
    $_SESSION['error'] = "Invalid username or password!";
    header("Location: ../mainPage/login.html");
    exit();

    if (isset($_SESSION['error'])) {
        echo "<div class='alert alert-danger text-center' role='alert'>" . $_SESSION['error'] . "</div>";
        unset($_SESSION['error']); // Clear the error after displaying it
        }
}
?>