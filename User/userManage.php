<?php
include("../database/db_conn.php");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    header("Location:deleteUser.php?id=$id");
    exit;
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    header("Location:editUser.php?id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Manage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/navigation.css">
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href="../css/manageUser.css">
    <script src="../script/adminNavBar.js" defer></script>

    <style>
        
        .addUser {
            margin-bottom: 10px;
            display: flex;
            justify-content: flex-end;
        }

        .add-button {
            background-color: #1D5748;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            margin-bottom: 10px;
        }
    </style>

<script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this user record!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `editUser.php?id=${id}`;
                }
            });
        }
    </script>
</head>

<body>
    <!-- Navigation bar -->
    <div id="navbar"></div>

    <div class="search-filter">
                <input style="width: 55%;" type="text" placeholder="Search">
                <select style="width: 15%;">
                    <option value="" disabled selected>Role</option>
                    <option value="admin">Admin</option>
                    <option value="officer">School Officer</option>
                </select>
                <button class="filter-button" style="width: 15%;">Search</button>
    </div>

    <div class="container">
        <div class="addUser"><a href="addUser.php" class="add-button">Add User</a></div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Responsible School</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM User";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['username'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['school'] . "</td>";
                            echo "<td>" . $row['role'] . "</td>";
                     
                            echo "<td>
                                    <a href='?edit=" . $row['id'] . "' class='btn btn-primary btn-sm me-2'>Edit</a>
                                    <a href='?delete=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No User found.</td></tr>";
                    }
                    ?>
                </tbody>
        </div>
    </div>
</body>

</html>