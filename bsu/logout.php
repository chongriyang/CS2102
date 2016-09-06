<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_COOKIE['user']) && !empty(isset($_COOKIE['user']))) {
	include("open_connection.php");
	setcookie('user', "", time() - 3600, "/", "", 0);
	$user_id = $_SESSION['user_id'];
	$query_select_user = "SELECT user_id FROM cookie WHERE user_id = '$user_id' LIMIT 1";
	$result_select_user = pg_query($query_select_user) or die('Query failed: ' . pg_last_error());
	if ($result_select_user) {
		$query_delete_cookie = "DELETE FROM cookie WHERE user_id = '$user_id'";
		$result_delete_cookie = pg_query($query_delete_cookie) or die('Query failed: ' . pg_last_error());
		if ($result_delete_cookie) {
		//Successfully delete entry from cookie table
		}
	}
	session_destroy();
	include("close_connection.php");
}

session_start();

if (!empty($_POST['login_submit'])) {
	include_once("open_connection.php");
	$email = trim($_POST['email']);
	$email = strtolower($email);
	$password = strip_tags($_POST['password']);

	$salt = "F3#@$%ewgSDGaskjf#@$EFsdFGqwjfqad@#$^$%&segjlkszflijs";
	$password = hash('sha256', $salt.$password);

	$query = "SELECT email, password, user_id, name, is_admin FROM person WHERE email = '$email' AND is_activated = '1' LIMIT 1";

	if (!empty($email) && !empty($password)) {
		if (!preg_match('/[^A-Za-z0-9\@.]/', $email)) {
			$result = pg_query($query) or die('Query failed: ' . pg_last_error());
			if ($result) {
				$row = pg_fetch_row($result);
				$db_email = $row[0];
				$db_password = $row[1];
				$db_user_id = $row[2];
				$db_username= $row[3];
				$db_is_admin= $row[4];

				if ($email == $db_email && $password == $db_password) {
					$_SESSION['username'] = $db_username;
					$_SESSION['user_id'] = $db_user_id;
					if ($db_is_admin === 't') {
						header('Location: administator.php');
						die();
					} else {
						header('Location: user.php');
						die();
					}
				} else if ($email == $db_email && $password != $db_password) {
					echo "Your email account or password is incorrect. Please try again.";
				} else {
					echo "The account doesn't exist. If you do not have an account, please sign up.";
				}
			}
		} else {
			echo "You entered an invalid email account with special characters (e.g. '!', '$', '#'). Please omit them and try again.";
		}
	} else if (!empty($email)) {
		if (!preg_match('/[^A-Za-z0-9\@.]/', $email)) {
			echo "Please enter your password.";
		} else {
			echo "You entered an invalid email account with special characters (e.g. '!', '$', '#'.). Please omit them and try again.";
		}
	} else if (!empty($password)) {
		echo "Please enter your email account.";
	} else {
		echo "Please enter your email account and password.";
	}
	include_once("close_connection.php");
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
					<span class="icon-bar"></span>
				</button>

				<a class="navbar-brand" href="index.php">CrowdFunding</a>
			</div>
			<div class="collapse navbar-collapse" id="?">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="#">Browse</a></li>
					<li><a href="#">Create a Project</a></li>
					<li><a href="#">Gallery</a></li>
					<li><a href="search.php">Search</a></li>
					<li><a href="sign_up.php">Sign Up</a></li>

					<div>
						<button style="position:absolute;margin: 0;height: 3.5em" type="button" class="btn btn-success" data-toggle="modal" data-target="#loginPopUpWindow">Sign In</button>
					</div>
					<li>
						<div class="modal fade" id="loginPopUpWindow">
							<div class="modal-dialog">
								<div class="modal-content">

									<!-- header -->
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h3 class="modal-title">Log In</h3>
									</div>

									<!-- body (form) -->
									<div class="modal-body">
										<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form-signin">
											<label for="input_email" class="sr-only">Email address</label>
											<input type="email" id="input_email" class="form-control" placeholder="Email address" name="email" required autofocus>
											<label for="input_Password" class="sr-only">Password</label>
											<input type="password" id="input_password" class="form-control" placeholder="Password" name ="password" required>
											<div class="checkbox">
												<label>
													<input type="checkbox" value="input_remember_me"> Remember me
												</label>
											</div>
											<button class="btn btn-lg btn-primary btn-block" type="submit" name="login_submit" value="login_submit">Sign in</button>
										</form>
									</div>

								</div>
							</div>
						</div>
					</li>

				</ul>
			</div>
		</div>
	</nav>

	<style>
		.bg-1 { 
			background-color: #1abc9c; /* Green */
			color: #ffffff;
		}
		.bg-2 { 
			background-color: #474e5d; /* Dark Blue */
			color: #ffffff;
		}
		.bg-3 { 
			background-color: #ffffff; /* White */
			color: #555555;
		}
	</style>

	<div class="container-fluid bg-2 text-center">
		<h3>Thank you for visiting crowdfunding!</h3>
		<p>You have successfuly log out.</p>
	</div>

	<div class="container-fluid bg-3 text-center">
		<h3>Contact us</h3>
		<p>Singapore</p>
	</div>

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