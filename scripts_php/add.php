<?php
    session_start();
    $con = mysqli_connect("localhost","root","","moviesite");
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $userid = $_POST['user_id'];
    $movieid = $_POST['movie_id'];

    // Check if the movie is already added
    $checkQuery = "SELECT * FROM `moviesowned` WHERE user_id = ? AND movie_id = ?";
    $checkStmt = $con->prepare($checkQuery);
    $checkStmt->bind_param("ii", $userid, $movieid);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "Movie already added to favorites.";
    } else {
        // Movie is not added, proceed with the INSERT
        $insertQuery = "INSERT INTO `moviesowned` (user_id, movie_id) VALUES (?, ?)";
        $insertStmt = $con->prepare($insertQuery);
        $insertStmt->bind_param("ii", $userid, $movieid);
        $insertStmt->execute();

        if ($insertStmt->affected_rows > 0) {
            echo "Movie added successfully.";
        } else {
            echo "Failed to add the movie.";
        }
    }

    $checkStmt->close();
    $insertStmt->close();
    $con->close();
?>
