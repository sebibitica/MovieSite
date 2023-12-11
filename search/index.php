<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Search - Movie Shop Website</title>
  <link rel="stylesheet" href="search.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      // When the search input changes
      $('input[name="search"]').on('input', function() {
        var searchTerm = $(this).val();

        // Send the search term to the server
        $.ajax({
          type: 'GET',
          url: 'search.php',
          data: {
            search: searchTerm
          },
          success: function(response) {
            // Update the results container with the response
            $('#search-results').html(response);
          }
        });
      });
    });
  </script>
</head>

<body>
  <header>
    <div class="logo">
      <a href="../"><img src="../logo.png" alt="Movies" /></a>
    </div>
    <nav>
      <ul>
        <li class="dropdown">
          <a href="./">Search</a>
          <div class="dropdown-content">
            <a href="./category/">Search Category</a>
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
    <h1>Search Movies</h1>
    <form>
      <input type="text" name="search" placeholder="Enter a movie title" />
    </form>
    <div id="search-results"></div>
  </main>
  <footer>
    <p>&copy; 2023 MovieSite. All rights reserved.</p>
  </footer>
</body>

</html>
