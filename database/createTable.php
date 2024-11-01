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

mysqli_close($conn);
?>