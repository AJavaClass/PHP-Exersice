<?php

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{ width: 360px; padding: 20px; }
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>

<h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome.</h1>

<p>
    <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
    <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
</p><br>

<div style="margin:20px;text-align:center;" class="form-group">
  <form action="createDB.php" method="get">
    <input class="btn btn-primary" type="submit" value="Δημιουργία ΒΔ">
  </form><br>
  <form action="searchForm.php" method="get">
    <input class="btn btn-primary" type="submit" value="Αναζήτηση">
  </form><br>
  <form action="showDB.php" method="get">
    <input class="btn btn-primary" type="submit" value="Δείξε Στοιχεία">
  </form><br>
</div>

</body>

</html>
