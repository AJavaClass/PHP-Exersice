<?php

require_once "config.php";

$sql = "CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
  header("location: dashboard.php");
  echo "<br>Table created successfully!";
} else {
  header("location: dashboard.php");
  echo "<br>Error creating table: " . $conn->error;
}

$conn->close();

exit;
?>
