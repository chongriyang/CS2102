  <?php
  error_reporting(E_ALL & ~E_NOTICE);
  session_start();

  if (time() > $_SESSION['timeout']) {
  	$_SESSION['username'] = null;
  	$_SESSION['user_id'] = null;
  	$_SESSION['is_admin'] = null;
  	$_SESSION['timeout'] = time()+1800;

  	$username = null;
  	$user_id = null;
  	$is_admin = null;
  	header('Location: /crowd_funding/index.php');
  	die();
  }

  if (isset($_SESSION['username']) && isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && !empty(isset($_SESSION['username'])) && !empty(isset($_SESSION['user_id'])) && !empty(isset($_SESSION['is_admin']))) {
  	$_SESSION['timeout'] = time() + 1800;
  	$username = $_SESSION['username'];
  	$user_id = $_SESSION['user_id'];
  	$is_admin = $_SESSION['is_admin'];
  } else {
  	header('Location: /crowd_funding/index.php');
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
  <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/header/navbar.php'); ?>
  
  	</body>

  	<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/header/footer.php'); ?>

  	</html>