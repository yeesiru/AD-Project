<?php
// Ensure the userID is available
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


// Fetch other users (limited info)
$sql = "SELECT name, role, gender, email, phone, school, image FROM User WHERE userID != ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $userID);
mysqli_stmt_execute($stmt);
$otherUsers = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>

<head>
    <title>SJAM Connect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="../script/officerNavBar.js" defer></script>
    <link rel="stylesheet" href="../css/navigation.css">
    <link rel="stylesheet" href="../css/otherUser.css">

    <style>
        nav{
            padding: 20px;
            font-size: 20px;
            padding-left:50px;
        }

    </style>
</head>

<body>
    <div class="top">
        <div class="container">
            <div class="logo">
                <img src="../img/SJAM-logo.png"/>
                <p>SJAM Connect</p>
            </div>
        </div>
    </div>

    <!-- Navigation bar -->
    <nav>
        <a href="#" onclick="history.back();" style="text-decoration:none; color: white;"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="white"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg> Back</a>
    </nav>
        
    <div class="swiper-background">
        <div class="swiper">
        <div class="slider-wrapper swiper-wrapper">
            <?php while ($user = mysqli_fetch_assoc($otherUsers)): ?>
                <div class="card-list swiper-slide">
                    <div class="card-item">
                        <img src="<?= $user['image'] ?: 'uploads/default.png'; ?>" alt="User Image" class="user-image">
                        <h2 class="user-name"><?= htmlspecialchars($user['name']); ?></h2>
                        <p class="user-profession"><?= htmlspecialchars($user['role']); ?></p>
                        <p class="user-profession"><?= htmlspecialchars($user['school']); ?></p>
                        <button 
                            class="message-button" 
                            onclick="showUserDetails('<?= htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8'); ?>')">
                            More Details
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        </div>
    </div>
</div>


     <!-- Modal for User Details -->    
     <div id="userModal" class="userModal">
        <div class="close-btn">
        <p id="close-popup" onclick="closeModal()">x</p>
        </div>

        <div class="modalContent">
            <div class="popup-image">
                <img id="modalImage" src="" alt="User Image">
            </div>

            <div class="popup-details">
            <p id="modalName"></span></p>
            <p><strong>Role:</strong><span  id="modalRole"></span></p>
            <p><strong>Gender:</strong><span  id="modalGender"></span></p>
            <p><strong>Email:</strong><span  id="modalEmail"></span></p>
            <p><strong>Phone Number:</strong><span  id="modalPhone"></span></p>
            <p><strong>Responsible School:</strong><span  id="modalSchool"></span></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="../script/swipper.js"></script>
    <script src="../script/otherUser.js"></script>
</body>

</html>