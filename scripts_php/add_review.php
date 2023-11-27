<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['id'])) {
        // Handle the case where the user is not logged in
        echo "You need to be logged in to add a review.";
    } else {
        // Get the data from the form
        $user_id = $_SESSION['id'];
        $user_name= isset($_SESSION['username']) ? $_SESSION['username'] : 'null';
        $movie_id = $_POST['movie_id'];
        $review_text = $_POST['review_text'];
        $rating = $_POST['rating'];

        $con = mysqli_connect('localhost', 'root', '', 'moviesite');

        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $query = "INSERT INTO reviews (user_id, user_name, movie_id, review_text, rating) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("isisi", $user_id, $user_name, $movie_id, $review_text, $rating);

        if ($stmt->execute()) {
            echo "Review added successfully!";
        } else {
            echo "Error adding review: " . $stmt->error;
        }

        $stmt->close();
        $con->close();
    }
} else {
    echo "Invalid request.";
}
?>