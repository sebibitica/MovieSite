<?php
$api_key = 'a80e29ac528ddd8cf4409afced5495e1';
$genre_endpoint = 'https://api.themoviedb.org/3/genre/movie/list';
$discover_endpoint = 'https://api.themoviedb.org/3/discover/movie';

$genres_json = file_get_contents("$genre_endpoint?api_key=$api_key");
$genres_data = json_decode($genres_json, true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Browser</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">
      <a href="../../"><img src="../../logo.png" alt="Movies" /></a>
    </div>
    <nav>
      <ul>
        <li class="dropdown">
          <a href="../">Search</a>
          <div class="dropdown-content">
            <a href="./">Search Category</a>
          </div>
        </li>
        <li><a href="../../account/">Account</a></li>
        <li class="dropdown">
          <a href="../../about/">About</a>
          <div class="dropdown-content">
            <a href="../../about/">About Us</a>
            <a href="../../about/movies.html">About Movies</a>
          </div>
        </li>
      </ul>
    </nav>
  </header>

  <main>
    <div class="welcome">
        <form method="get" action="">
            <h3 class="textGenre">Select Genre:<h3>
            <select name="genre" id="genre">
                <?php
                foreach ($genres_data['genres'] as $genre) {
                    echo "<option value=\"{$genre['id']}\">{$genre['name']}</option>";
                }
                ?>
            </select>
            <input type="submit" value="Show Category">
        </form>
    </div>
    <section class="movies">
      <br>
      <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['genre'])) {
            //API Call
            $selected_genre = $_GET['genre'];
            $discover_url = "$discover_endpoint?api_key=$api_key&with_genres=$selected_genre&sort_by=popularity.desc";

            $discover_json = file_get_contents($discover_url);
            $discover_data = json_decode($discover_json, true);

            // Display the Results
            $genre_id=$_GET['genre'];
            $genre_name = array_column($genres_data['genres'], 'name', 'id')[$genre_id];
            echo '<h2>Top ' . $genre_name . ' Movies:</h2>';
            echo '<div class="movie-list">';

            foreach ($discover_data['results'] as $movie) {
                echo '<a class="movie" href="../../movie/?id=' . $movie['id'] . '">';
                echo '<img src="https://image.tmdb.org/t/p/w500' . $movie['poster_path'] . '" class="image"/>';
                echo '<div class="details">';
                echo '<div class="nameofmovie"><h1>' . $movie['title'] . '</h1></div>';
                echo '</div></a>';
            }
        }

        ?>
      </div>
    </section>
  </main>

  <footer>
    <p>&copy; 2023 MovieSite. All rights reserved.</p>
  </footer>


</body>
</html>



