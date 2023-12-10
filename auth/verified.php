<?php
require 'vendor/autoload.php'; 

session_start();
$con = mysqli_connect("localhost", "root", "", "moviesite");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $query = "UPDATE `users` SET isEmailConfirmed = '1' WHERE token = '$token'";
    $result = mysqli_query($con, $query);

    if ($result) {
        echo "<div class='form'>
              <h3>Email verification successful. You can now <a href='login.php'>login</a>.</h3>
              </div>";
    } else {
        echo "<div class='form'>
              <h3>Email verification failed. Please contact support.</h3>
              </div>";
    }
} else {
    echo "<div class='form'>
          <h3>Invalid verification link.</h3>
          </div>";
}
?>