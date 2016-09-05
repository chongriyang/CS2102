  <?php
  error_reporting(E_ALL & ~E_NOTICE);
  session_start();

  if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
  	$username = $_SESSION['username'];
  	$user_id = $_SESSION['user_id'];
  } else {
  	header('Location: index.php');
  	die();
  }
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <title>CrowdFunding</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
  <body>

  <nav class="navbar navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="dropdown"></span> 
        </button>
        <a class="navbar-brand" href="user.php">CrowdFunding</a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#">Browse</a></li>
          <li><a href="#">Create a Project</a></li>
          <li><a href="#">Gallery</a></li>
          <li><a href="search.php">Search</a></li>
          <li>
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" type="text">Welcome <?php echo $username ?> <span class="caret"></span></a>
              <ul class="dropdown-menu">
              	<li><a href="#"></a></li>
                  <li><a href="#">My Projects</a></li>
                  <li><a href="#">Funded Projects</a></li>
                   <li><a href="#">Bookmarked Projects</a></li>
                    <li><a href="#">Transactions</a></li>
                  <li><a href="#">Edit Profile</a></li>
                  <li><a href="logout.php">Log Out</a></li>
              </ul>
        </ul>
      </div>
    </div>
  </nav>
  </body>

  <style>
  .bg-4 {
    position:fixed;
    left:0px;
      bottom:0px;
      height:30px;
      width:100%;
      background-color: #2f2f2f;
      color: #ffffff;
  }
  </style>

  <footer class="container-fluid bg-4 text-center">
    <p><a href="about_us.php">About Us</a></p>
  </footer>

  </html>