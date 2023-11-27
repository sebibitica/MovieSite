<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registration</title>
    <link rel="stylesheet" href="style_auth.css"/>
</head>
<body>
<header>
    <a href="../"><img class="logo" src="../logo.png" alt="Movies" /></a>
    <nav>
      <ul>
        <li><a href="../search/">Search</a></li>
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
<?php
    session_start();
    $con = mysqli_connect("localhost","root","","moviesite");
    if(!$con){
        die("Connection failed: " . mysqli_connect_error());
    }
    if(isset($_SESSION['username'])){
        header("Location: ../account/");
        exit();
    }
    if (isset($_REQUEST['username'])) {
        $username = stripslashes($_REQUEST['username']);
        $username = mysqli_real_escape_string($con, $username);
        $email    = stripslashes($_REQUEST['email']);
        $email    = mysqli_real_escape_string($con, $email);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($con, $password);
        $query    = "INSERT into `users` (username, password, email)
                     VALUES ('$username', '" . md5($password) . "', '$email')";
        $result   = mysqli_query($con, $query);
        if ($result) {
            echo "<div class='form'>
                  <h3>You are registered successfully.</h3><br/>
                  <p class='link'>Click here to <a href='login.php'>Login</a></p>
                  </div>";
        } else {
            echo "<div class='form'>
                  <h3>Required fields are missing.</h3><br/>
                  <p class='link'>Click here to <a href='registration.php'>registration</a> again.</p>
                  </div>";
        }
    } else {
?>
    <main>
    <form class="form" action="" method="post">
        <h1 class="login-title">Registration</h1>
        <input type="text" class="login-input" name="username" placeholder="Username" required />
        <input type="text" class="login-input" name="email" placeholder="Email Adress">
        <input type="password" class="login-input" name="password" placeholder="Password">
        <input type="submit" name="submit" value="Register" class="login-button">
        <p class="link"><a href="login.php">Click to Login</a></p>
    </form>
    </main>
<?php
    }
?>
    <footer>
        <p>&copy; 2023 MovieSite. All rights reserved.</p>
    </footer>
</body>
</html>