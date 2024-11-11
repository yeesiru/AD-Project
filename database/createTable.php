<?php

require_once("db_conn.php");

$sql1 = "CREATE TABLE IF NOT EXISTS User (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL ,
    password VARCHAR(255) NOT NULL ,
    role ENUM('admin','officer') NOT NULL ,
    name VARCHAR(255) NOT NULL,
    gender ENUM('female','male') NOT NULL ,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    school VARCHAR(255) NOT NULL
    )";

if (mysqli_query($conn, $sql1)) {
    echo "Table User created successfully";
  } else {
    echo "Error creating table: " . mysqli_error($conn);
  }

// SQL to create equipment table
$sql2 = "CREATE TABLE IF NOT EXISTS equipment (
  id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  type VARCHAR(255) NOT NULL,
  equipment VARCHAR(255) NOT NULL,
  quantity INT(11) NOT NULL
)";

if (mysqli_query($conn, $sql2)) {
  echo "Table equipment created successfully<br>";
} else {
  echo "Error creating equipment table: " . mysqli_error($conn) . "<br>";
}

// SQL to create ambulances table
$sql3 = "CREATE TABLE IF NOT EXISTS ambulance (
  id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  vehicleId VARCHAR(11) NOT NULL,
  type ENUM('Basic Life Support', 'Advanced Life Support', 'Critical Care') NOT NULL,
  capacity INT(11) NOT NULL,
  availability ENUM('Available', 'Unavailable') NOT NULL
)";

if (mysqli_query($conn, $sql3)) {
  echo "Table ambulances created successfully<br>";
} else {
  echo "Error creating ambulances table: " . mysqli_error($conn) . "<br>";
}

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
  echo "Table feedback created successfully.<br>";
} else {
  echo "Error creating feedback table: " . mysqli_error($conn) . "<br>";
}

//SQL to create hall table
$sql5 = "CREATE TABLE IF NOT EXISTS halls (
    hall_id VARCHAR(10) PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    capacity INT NOT NULL,
    location VARCHAR(100),
    facility VARCHAR(100)
)";

if (mysqli_query($conn, $sql5)) {
  echo "Table hall created successfully.<br>";
} else {
  echo "Error creating hall table: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
?>