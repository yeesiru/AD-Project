<?php

require_once("db_conn.php");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the User table
$sql1 = "CREATE TABLE IF NOT EXISTS User (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    userID VARCHAR(255) UNIQUE,
    username VARCHAR(255) UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'officer') NOT NULL,
    name VARCHAR(255) NOT NULL,
    gender ENUM('female', 'male') NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    school VARCHAR(255) NOT NULL,
    image VARCHAR(255)
)";
if (mysqli_query($conn, $sql1)) {
    echo "Table 'User' created successfully.<br>";
} else {
    echo "Error creating 'User' table: " . mysqli_error($conn) . "<br>";
}

// Create the Equipment table
$sql2 = "CREATE TABLE IF NOT EXISTS equipment (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(255) NOT NULL,
    equipment VARCHAR(255) NOT NULL,
    quantity INT(11) NOT NULL
)";
if (mysqli_query($conn, $sql2)) {
    echo "Table 'equipment' created successfully.<br>";
} else {
    echo "Error creating 'equipment' table: " . mysqli_error($conn) . "<br>";
}

// Create the Ambulance table
$sql3 = "CREATE TABLE IF NOT EXISTS ambulance (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vehicleId VARCHAR(11) NOT NULL,
    type ENUM('Basic Life Support', 'Advanced Life Support', 'Critical Care') NOT NULL,
    capacity INT(11) NOT NULL,
    availability ENUM('Available', 'Unavailable') NOT NULL
)";
if (mysqli_query($conn, $sql3)) {
    echo "Table 'ambulance' created successfully.<br>";
} else {
    echo "Error creating 'ambulance' table: " . mysqli_error($conn) . "<br>";
}

// Create the Feedback table
$sql4 = "CREATE TABLE IF NOT EXISTS feedback (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    serviceType VARCHAR(50) NOT NULL,
    rating INT,
    feedbackText TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'responded') DEFAULT 'pending',
    admin_response TEXT
)";
if (mysqli_query($conn, $sql4)) {
    echo "Table 'feedback' created successfully.<br>";
} else {
    echo "Error creating 'feedback' table: " . mysqli_error($conn) . "<br>";
}



//SQL to create hall table
$sql5 = "CREATE TABLE IF NOT EXISTS hall (
    hall_id VARCHAR(10) PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    capacity INT NOT NULL,
    location VARCHAR(100),
    facility VARCHAR(100)
)";

if (mysqli_query($conn, $sql5)) {
    echo "Table 'hall' created successfully.<br>";
} else {
    echo "Error creating 'hall' table: " . mysqli_error($conn) . "<br>";
}

// Create the Equipment Booking table
$sql6 = "CREATE TABLE IF NOT EXISTS equipment_booking (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    equipment_id INT(11) UNSIGNED NOT NULL,
    user_id INT(11) UNSIGNED NULL,
    quantity INT(11) NOT NULL,
    booking_date DATE NOT NULL,
    FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES User(id) ON DELETE CASCADE
)";
if (mysqli_query($conn, $sql6)) {
    echo "Table 'equipment_booking' created successfully.<br>";
} else {
    echo "Error creating 'equipment_booking' table: " . mysqli_error($conn) . "<br>";
}

//SQL to create hall booking table
$sql8 = "CREATE TABLE IF NOT EXISTS hallBooking (
    booking_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hall_id VARCHAR(10) NOT NULL, 
    booked_by VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    time_slot VARCHAR(50) NOT NULL,
    FOREIGN KEY (hall_id) REFERENCES hall(hall_id)
)";


if (mysqli_query($conn, $sql8)) {
  echo "Table hall booking created successfully<br>";
} else {
  echo "Error creating hall booking table: " . mysqli_error($conn) . "<br>";
}

// SQL to create ambulances booking table
$sql7 = "CREATE TABLE IF NOT EXISTS ambulanceBooking (
  id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  contact VARCHAR(15) NOT NULL,
  destination VARCHAR(255) NOT NULL,
  booking_time TIME NOT NULL,
  booking_date DATE NOT NULL,
  vehicleId VARCHAR(11) NOT NULL,
  FOREIGN KEY (vehicleId) REFERENCES ambulance(vehicleId) 
  ON DELETE CASCADE
  ON UPDATE CASCADE
)";

if (mysqli_query($conn, $sql7)) {
  echo "Table ambulances booking created successfully<br>";
} else {
  echo "Error creating ambulances table: " . mysqli_error($conn) . "<br>";
}



// Close the connection
mysqli_close($conn);
?>
