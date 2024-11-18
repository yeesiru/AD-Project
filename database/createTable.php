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

// SQL to create ambulances booking table
$sql4 = "CREATE TABLE IF NOT EXISTS ambulanceBooking (
  id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  contact VARCHAR(15) NOT NULL,
  destination VARCHAR(255) NOT NULL,
  booking_time TIME NOT NULL,
  booking_date DATE NOT NULL,
  vehicleId VARCHAR(11),
  FOREIGN KEY (vehicleId) REFERENCES ambulance(vehicleId)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)";


if (mysqli_query($conn, $sql4)) {
  echo "Table ambulances booking created successfully<br>";
} else {
  echo "Error creating ambulances table: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
?>