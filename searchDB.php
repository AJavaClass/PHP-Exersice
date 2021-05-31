<!DOCTYPE html>
<html>
<title>Search Results</title>
<head>
<style>
table, th, td {
     border: 1px solid black;
     text-align: center;
}
table {
  margin: auto;
}
p {
  text-align: center;
  font-size: 34px;
}
</style>
</head>
<body>
<?php

$fullname = $_POST["full_name"];
$email = $_POST["email"];
$gender = $_POST["sex"];
$country = $_POST["country"];
$programming_language = $_POST["programming_language"];

require_once "config.php";

$sql = "SELECT id, full_name, email, gender, country, programming_language FROM forms WHERE full_name='".$fullname."' AND email='".$email."' AND gender='".$gender."' AND country='".$country."' AND programming_language='".$programming_language."'";
$result = $conn->query($sql);

if ($result !== false && $result->num_rows > 0) {
     echo "<table><tr><th>ID</th><th>Name</th><th>Email</th><th>Gender</th><th>Country</th><th>Favorite Programming Language</th></tr>";
     // output data of each row
     while($row = $result->fetch_assoc()) {
         echo "<tr><td>" . $row["id"]. "</td><td>" . $row["full_name"]. "</td><td>" . $row["email"]. "</td><td>" . $row["gender"]. "</td><td>" . $row["country"]. "</td><td>" . $row["programming_language"]. "</td></tr>";
     }
     echo "</table>";
} else {
     echo "<p>0 results</p>";
}

$conn->close();
?>

</body>
</html>
