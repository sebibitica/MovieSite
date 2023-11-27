<?php
// Check if the search term is provided
if (isset($_GET['search'])) {
  $searchTerm = $_GET['search'];

  $api_key="a80e29ac528ddd8cf4409afced5495e1";
  $url = "https://api.themoviedb.org/3/search/movie?api_key=$api_key&query=" .urlencode ($searchTerm);
  $response = file_get_contents($url);
  $response = json_decode($response, true);

  if ($response && isset($response['results']) && count($response['results']) > 0) {
      foreach ($response['results'] as $movie) {
          ?>
          <a class="movie" href="../movie/?id=<?php echo $movie['id']; ?>">
                <!-- if there is image show it if not show another picture -->
            <?php if ($movie['poster_path']) { ?>
              <img src="https://image.tmdb.org/t/p/w500<?php echo $movie['poster_path']; ?>" class="image" />
            <?php } else { ?>
                <img src="../movies_images/no_image.jpg" class="image" />
            <?php } ?>
              <div class="details">
                  <h1><?php echo $movie['title']; ?></h3>
              </div>
          </a>
          <?php
      }
  } else {
      echo "<p>No movies found.</p>";
  }
}
?>
