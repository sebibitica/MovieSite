<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Movie Shop Website</title>
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <header>
    <div class="logo">
      <a href="./"><img src="logo.png" alt="Movies" /></a>
    </div>
    <nav>
      <ul>
        <li><a href="search/">Search</a></li>
        <li><a href="account/">Account</a></li>
        <li class="dropdown">
          <a href="about/">About</a>
          <div class="dropdown-content">
            <a href="about/">About Us</a>
            <a href="about/movies.html">About Movies</a>
          </div>
        </li>
      </ul>
    </nav>
  </header>

  <main>
    <div class="welcome">
      <text class="welcome-title">Welcome to MovieSite!</text>
      <span style="font-size:36px">📽️</span>
    </div>
    <section class="movies">
      <br>
      <h2>Here are the top Movies:</h2>
      <div class="movie-list">
        <?php
          $api_key="a80e29ac528ddd8cf4409afced5495e1";
          $url="https://api.themoviedb.org/3/movie/popular?api_key=$api_key&language=en-US&page=1";
          $movies=file_get_contents($url);
          $movies=json_decode($movies,true);
        
          foreach($movies['results'] as $movie):
        ?>
          <a class="movie" href="movie/?id=<?php echo $movie['id']; ?>">
            <img src="https://image.tmdb.org/t/p/w500<?php echo $movie['poster_path']; ?>" class="image"/>
            <div class="details">
                <div class="nameofmovie">
                    <h1><?php echo $movie['title']; ?></h3>
                </div>
            </div>
          </a>
        <?php endforeach;?>
      </div>

    </section>
  </main>

  <footer>
    <p>&copy; 2023 MovieSite. All rights reserved.</p>
  </footer>
  <script>
    const title = document.querySelector('.welcome-title');
    const text = title.textContent;
    const letters = text.split('');

    const coloredText = letters.map(letter => `<span>${letter}</span>`).join('');
    title.innerHTML = coloredText;
  </script>
</body>

</html>