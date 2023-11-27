<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php"); // Redirect to login page if not logged in
    exit();
}

// Check if review_id is set
if (isset($_POST['review_id'])) {
    $review_id = $_POST['review_id'];

    // Database connection
    $con = mysqli_connect("localhost", "root", "", "moviesite");

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $deleteQuery = "DELETE FROM reviews WHERE id = ?";
    $deleteStmt = $con->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $review_id);
    $deleteStmt->execute();
    $movie_id = $_POST['movie_id'];

    // Check if the deletion was successful
    if ($deleteStmt->affected_rows > 0) {
        header("Location: ../movie/?id=$movie_id");
        exit();
    } else {
        header("Location: ../movie/?id=$movie_id&error=delete_failed");
        exit();
    }

    $deleteStmt->close();
    $con->close();
} else {
    header("Location: ../index.php");
    exit();
}
?>