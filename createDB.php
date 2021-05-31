<?php

require_once "config.php";

$sql = "CREATE TABLE forms (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    gender SET('male','female') NOT NULL,
    country VARCHAR(100) NOT NULL,
    programming_language SET('Java','Python','C++','Ruby','Javascript','PHP','C#','R') NOT NULL
    )";
if ($conn->query($sql) === TRUE) {
  echo "<br>Table created successfully!";
} else {
  echo "<br>Error creating table: " . $conn->error;
}

$conn->close();

exit;

?>
