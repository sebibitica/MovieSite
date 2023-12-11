<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>
<body>
    <header>
        <a href="../"><img class="logo" src="../logo.png" alt="Movies" /></a>
        <nav>
        <ul>
            <li class="dropdown">
                <a href="../search/">Search</a>
                <div class="dropdown-content">
                    <a href="../search/category/">Search Category</a>
                </div>
            </li>
            <li><a href="../account/">Account</a></li>
            <li class="dropdown">
          <a href="../about/">About</a>
          <div class="dropdown-content">
            <a href="../about/">About Us</a>
            <a href="../about/movies.html">About Movies</a>
          </div>
        </li>
        </ul>
        </nav>
    </header>

    <main>
        <?php

            session_start();

            function time_elapsed_string($datetime, $full = false) {
                date_default_timezone_set('Europe/Bucharest');
                $now = new DateTime;
                $ago = new DateTime($datetime);
                $diff = $now->diff($ago);
                $w = floor($diff->d / 7);
                $diff->d -= $w * 7;
                $string = ['y' => 'year','m' => 'month','w' => 'week','d' => 'day','h' => 'hour','i' => 'minute','s' => 'second'];
                foreach ($string as $k => &$v) {
                    if ($k == 'w' && $w) {
                        $v = $w . ' week' . ($w > 1 ? 's' : '');
                    } else if (isset($diff->$k) && $diff->$k) {
                        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                    } else {
                        unset($string[$k]);
                    }
                }
                if (!$full) $string = array_slice($string, 0, 1);
                return $string ? implode(', ', $string) . ' ago' : 'just now';
            }
            
            if(!isset($_GET['id'])){
                echo "<h1>Movie not found</h1>";
            }
            else{
                $movie_id=$_GET['id'];

                $api_key="a80e29ac528ddd8cf4409afced5495e1";
                $url="https://api.themoviedb.org/3/movie/$movie_id?api_key=$api_key&language=en-US";

                $result=file_get_contents($url);
                $result=json_decode($result,true);

                $con = mysqli_connect("localhost","root","","moviesite");
                if(!$con){
                    die("Connection failed: " . mysqli_connect_error());
                }

        ?>
            <div class="container">
                <!-- if there is picture show it, if not show another one -->
                <?php if ($result['poster_path']) { ?>
                    <img src="https://image.tmdb.org/t/p/w500<?php echo $result['poster_path']; ?>" class="movieimg"/>
                <?php } else { ?>
                    <img src="../movies_images/no_image.jpg" class="movieimg" />
                <?php } ?>
                <div class="detalii">
                    <div class="titlu_rating">
                        <h1><?php echo $result['title']; ?></h1>
                        <div class="rating">
                        <?php
                             //show the average rating
                            $query2 = "SELECT AVG(rating) AS average_rating FROM reviews WHERE movie_id = $movie_id";
                            $result2 = mysqli_query($con, $query2);
                            $row2 = mysqli_fetch_assoc($result2);
                            $average_rating = round($row2['average_rating'], 1);

                            //if there is no rating show None
                            if ($average_rating == 0) {
                                $average_rating = "None";
                            }

                            //get total number of reviews
                            $query3 = "SELECT COUNT(*) AS total_reviews FROM reviews WHERE movie_id = $movie_id";
                            $result3 = mysqli_query($con, $query3);
                            $row3 = mysqli_fetch_assoc($result3);
                            $total_reviews = $row3['total_reviews'];

                            echo "<h2>MovieSite rating: $average_rating"."⭐"."</h2>";
                            echo "<h4}>($total_reviews reviews)</h4>";
                        ?>
                        </div>
                    </div>
                    <br>
                    <br>
                    <h2>Description:</h2>
                    <br>
                    <div style="width:80%">
                        <?php echo '<h4 style="font-weight:500">'.$result['overview'].'</h4>'; ?>
                    </div>
                    <br>
                    <br>
                    <h2>Release date: <?php echo '<h4 style="font-weight:500">'.$result['release_date'].'</h4>'; ?></h2>
                    <br>
                    <br>
                    <h2>Official Score: <?php echo '<h4 style="font-weight:500">'.$result['vote_average'].'</h4>'; ?></h2>
                    <?php 
                        //see if the user has the movie in his favorites
                        $movie=$result['id'];
                        $userid=isset($_SESSION['id']) ? $_SESSION['id'] : null;
                        if ($userid !== null) {
                            $checkQuery = "SELECT * FROM `moviesowned` WHERE user_id = ? AND movie_id = ?";
                            $checkStmt = $con->prepare($checkQuery);
                            $checkStmt->bind_param("ii", $userid, $movie);
                            $checkStmt->execute();
                            $checkResult = $checkStmt->get_result();
                        
                            // If the user has the movie in their owned list, do not display the button
                            if ($checkResult->num_rows > 0) {
                                $displayButton = false;
                            } else {
                                $displayButton = true;
                            }
                        } else {
                            // User is not logged in, display the button
                            $displayButton = true;
                        }
                        
                        // Display the button if $displayButton is true
                        if ($displayButton) {
                            echo '<img src="../movies_images/add.png" class="addbtn1" data-id="' . $result['id'] . '"/>';
                        }
                        
                    ?>
                </div>
            </div>
            <form id="reviewForm" action="../scripts_php/add_review.php" method="post">
                <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
                <div class="review-input">
                <?php   
            $username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
            echo "<h3 class='name'> $username </h3>"; ?>
                    <textarea name="review_text" rows="7" cols="100" placeholder="Write your review" required></textarea>
                </div>
                <div class="rating_form">
                    <label for="rating_form">Rating:</label>
                    <div class="stars">
                        <input type="radio" name="rating" value="5" id="star1" >
                        <label for="star1">&#9733;</label>
                        <input type="radio" name="rating" value="4" id="star2">
                        <label for="star2">&#9733;</label>
                        <input type="radio" name="rating" value="3" id="star3">
                        <label for="star3">&#9733;</label>
                        <input type="radio" name="rating" value="2" id="star4">
                        <label for="star4">&#9733;</label>
                        <input type="radio" name="rating" value="1" id="star5" required>
                        <label for="star5">&#9733;</label>
                    </div>
                </div>
                <div class="submit-button">
                    <input type="submit" value="Submit Review">
                </div>
            </form>
        <?php
            $query = "SELECT * FROM reviews WHERE movie_id = $movie_id ORDER BY created_at DESC";

            $result = mysqli_query($con, $query);
            
            

            if (mysqli_num_rows($result) > 0) {
                echo "<div class='reviews'>";
                echo "<h2>Reviews:</h2>";
                
                while ($row = mysqli_fetch_assoc($result)) {
                    // Display each review with user name
                    echo "<div class='review'>";
                    echo "<h3 class='name'><strong>{$row['user_name']}</strong></h3>";
                    echo "<div class='linia-doi'>";
                    echo "<div>";
                    echo "<span class='rating_mic'>" . str_repeat('&#9733;', $row['rating']) . "</span>";
                    echo "<span class='date'>" . time_elapsed_string($row['created_at']) . "</span>";
                    echo "</div>";
                    echo "<div>";
                    if (isset($_SESSION['username']) && $_SESSION['username'] === $row['user_name']) {
                        echo "<form id='delete-form' action='../scripts_php/delete_review.php' method='post' style='display: inline;'>";
                        echo "<input type='hidden' name='review_id' value='{$row['id']}'>";
                        echo "<input type='hidden' name='movie_id' value='$movie_id'>";
                        echo "  <button type='submit' onclick='return confirm(\"Are you sure you want to delete this review?\")'>";
                        echo "    <img width='30' height='30' src='../movies_images/remove.png' alt='Delete Review'>";
                        echo "  </button>";
                        echo "</form>";
                    }
                    echo "</div>";
                    echo "</div>";
                    echo "<p class='content'>{$row['review_text']}</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No reviews yet.</p>";
            }
                echo "</div>";
            }
            mysqli_close($con);
        ?>
    </main>
    <script>
        $(document).ready(function(){
        $(".addbtn1").on("click",function(){
            console.log("bla");
            let movieid = $(this).data('id');
            let userid= <?php echo isset($_SESSION['id']) ? json_encode($_SESSION['id']) : 'null'; ?>;
            if(userid === null){
                alert("You need to login first");
            }
            else{
                $.ajax({
                url: "../scripts_php/add.php",
                type: "POST",
                data: {
                    movie_id: movieid,
                    user_id: userid
                },
                success: function(data){
                    location.reload();
                    alert("You have successfully added this movie to your Watchlist!\nYou can find it in your account page");
                },
                error: function(){
                    alert(response);
               }
                });
            }
        });
        });

        $(document).ready(function() {
        $('#reviewForm').submit(function(event) {
            event.preventDefault();

            // Get the form data
            var formData = $(this).serialize();

            // Send the form data to the server using AJAX
            $.ajax({
                type: 'POST',
                url: '../scripts_php/add_review.php',
                data: formData,
                success: function(response) {
                    // Display the success or error message
                    if (response.includes("Review added successfully")) {
                        location.reload();
                    }
                    else{
                        alert(response);
                        location.reload();
                    }
                },
            });
        });
    });

    </script>
</body>
</html>