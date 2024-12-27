<?php
include("../database/db_conn.php");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['edit'])) {
    $userID = $_GET['edit'];
    header("Location:editUser.php?userID=$userID");
    exit;
}

if (isset($_GET['view'])) {
    $userId = intval($_GET['view']); // Ensure ID is an integer
    $stmt = $conn->prepare("SELECT * FROM User WHERE userID = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit;
    }
}

// Pagination and Filtering Defaults
$rowsPerPage = 10; // Rows per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Current page
$searchQuery = isset($_GET['search']) ? $_GET['search'] : ''; // Search query
$roleFilter = isset($_GET['role']) ? $_GET['role'] : 'all'; // Role filter
$offset = ($page - 1) * $rowsPerPage; // Offset calculation

// Build SQL WHERE Clause
$whereClauses = [];
if (!empty($searchQuery)) {
    $whereClauses[] = "(username LIKE '%$searchQuery%' OR role LIKE '%$searchQuery%' OR name LIKE '%$searchQuery%')";
}
if ($roleFilter !== 'all') {
    $whereClauses[] = "role = '$roleFilter'";
}
$whereClause = count($whereClauses) > 0 ? "WHERE " . implode(" AND ", $whereClauses) : '';

// Paginated Data Query
$sql = "SELECT * FROM User $whereClause LIMIT $offset, $rowsPerPage";
$result = $conn->query($sql);

// Query to get the total number of users for calculating total pages
$totalRowsQuery = "SELECT COUNT(*) AS total FROM User $whereClause";
$totalRowsResult = $conn->query($totalRowsQuery);
$totalRows = $totalRowsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $rowsPerPage);


$idRow = $offset + 1;
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
    <link rel="stylesheet" href="../css/manageUser.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../script/adminNavBar.js" defer></script>

    <style>
        
        .addUser {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
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

        .title{
            text-align: left;
            margin-left: 10px;
            padding-bottom: 10px;
            padding-top: 10px;
            font-size: 20px;
            font-family: Arial, Helvetica, sans-serif;
            color: #1D5748;
            font-weight: bold;        
        }

    </style>
</head>

<body>
    <!-- Navigation bar -->
    <div id="navbar"></div>

    <!--Search bar-->
    <div class="search-filter">
        <input style="width: 55%;" type="text" id="search-bar" placeholder="Search">
        <button id="search-button" style="width: 15%;">Search</button>
    </div>

    <!--Filter button-->
    <div class="filter">
        <button class="button-value" onclick="filterUser('all')">All</button>
        <button class="button-value" onclick="filterUser('admin')">Admin</button>
        <button class="button-value" onclick="filterUser('officer')">Officer</button>    
    </div>

    <!--Pop up details-->
    <div id="user-popup" class="popup hide">
        <div class="popup-header">
                <span id="close-popup" class="close-btn">&times;</span>
        </div>

        <div class="popup-content">
            <div class="popup-image">
                <img id="popup-image" src="" alt="User Image" />
            </div>
            <div class="popup-details">
                <div class="popup-details-background">
                <h2 id="popup-name"></h2>
                <p><strong>User ID:</strong> <span id="popup-id"></span></p>
                <p><strong>Username:</strong> <span id="popup-username"></span></p>
                <p><strong>Role:</strong> <span id="popup-role"></span></p>
                <p><strong>Gender:</strong> <span id="popup-gender"></span></p>
                <p><strong>Email:</strong> <span id="popup-email"></span></p>
                <p><strong>Phone Number:</strong> <span id="popup-phone"></span></p>
                <p><strong>Responsible school:</strong> <span id="popup-school"></span></p>
                </div>
            </div>
        </div>
    </div>

    <!--User list table-->
    <div class="container">
        <div class="addUser">
            <div class="title">
                        User List
            </div>
            <a href="addUser.php" class="add-button">Add User</a>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>UserID</th>
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
                        $result = $conn->query($sql); // This query already contains LIMIT and OFFSET
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $idRow . "</td>";
                                echo "<td>" . htmlspecialchars($row['userID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['school']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['role']) . "</td>";  
                                
                                echo "<td> 
                                        <button class='btn btn-success btn-sm me-2 view-data' data-user-id='" . $row['userID'] . "'>View</button>
                                        <a href='?edit=" . $row['userID'] . "' class='btn btn-primary btn-sm me-2'>Edit</a>
                                        <button class='btn btn-danger btn-sm' onclick=\"confirmDelete('" . htmlspecialchars($row['userID'], ENT_QUOTES) . "')\">Delete</button>
                                        </td>";
                                echo "</tr>";

                                $idRow++; // Increment row number for the current page
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>No User found.</td></tr>";
                        }
                    ?>
                </tbody>
        </div>

        <!--Pagination-->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>" class="btn btn-secondary">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" 
                class="btn <?php echo ($i == $page) ? 'btn-primary' : 'btn-secondary'; ?>">
                <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>" class="btn btn-secondary">Next</a>
            <?php endif; ?>
        </div>

    </div>
</div>
</body>

</html>

<script>
    //Script for pop up details
    document.querySelectorAll('.view-data').forEach((button) => {
    button.addEventListener('click', () => {
        const userId = button.getAttribute('data-user-id');

        // Fetch user data from the server
        fetch(`fetchUserData.php?userID=${userId}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("User not found");
                }
                return response.json();
            })
            .then((data) => {
                showPopup(data);
            })
            .catch((error) => {
                alert(error.message);
            });
    });
});

function showPopup(data) {
    const popup = document.getElementById('user-popup');

    // Populate popup data
    document.getElementById('popup-image').src = data.image || 'default-image.jpg';
    document.getElementById('popup-name').innerText = data.name;
    document.getElementById('popup-id').innerText = data.userID;
    document.getElementById('popup-username').innerText = data.username;
    document.getElementById('popup-role').innerText = data.role;
    document.getElementById('popup-gender').innerText = data.gender;
    document.getElementById('popup-email').innerText = data.email;
    document.getElementById('popup-phone').innerText = data.phone;
    document.getElementById('popup-school').innerText = data.school;


    // Show the popup
    popup.classList.remove('hide');

    // Close button handler
    document.getElementById('close-popup').addEventListener('click', () => {
        popup.classList.add('hide');
    });
}

//Search
document.getElementById('search-button').addEventListener('click', function() {
    const query = document.getElementById('search-bar').value.toLowerCase();
    const rows = document.querySelectorAll('table tr');

    rows.forEach((row, index) => {
        if (index === 0) return; // Skip the header row
        const cells = row.querySelectorAll('td');
        const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
        
        if (rowText.includes(query)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

document.getElementById('search-bar').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('search-button').click();
    }
});


//Filter
function filterUser(value) {
    const rows = document.querySelectorAll('table tr');
    const buttons = document.querySelectorAll('.button-value');

    buttons.forEach(button => {
        button.classList.remove('active');
        if (button.textContent.toUpperCase() === value.toUpperCase()) {
            button.classList.add('active');
        }
    });

    rows.forEach((row, index) => {
        if (index === 0) return; // Skip the header row
        const roleCell = row.querySelector('td:nth-child(7)'); // Adjust column index as needed
        if (value === 'all' || (roleCell && roleCell.textContent.toLowerCase().includes(value.toLowerCase()))) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

window.onload=()=>{
filterUser("all");
};

//Delete user
function confirmDelete(userID) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will not be able to recover this user record!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `deleteUser.php?userID=${userID}`;
                }
            });
        }
</script>