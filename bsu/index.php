<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_COOKIE['user']) && !empty(isset($_COOKIE['user']))) {
	include_once("open_connection.php");
	$match = array();
	$query = array();

	$now = new DateTime();
	$now =$now->format('Y-m-d H:i:s');
	$now = strtotime($timeout);

	list($identifier, $token) = explode(':', $_COOKIE['user']);
	if (ctype_alnum($identifier) && ctype_alnum($token)) {

		$match['identifier'] = $identifier;
		$match['token'] = $token;

		$query['identifier'] = pg_escape_string($match['identifier']);
		$query['token'] = pg_escape_string($match['token']);

		$query_cookie = "SELECT user_id, timeout FROM cookie WHERE identifier = '{$query['identifier']}' AND key = '{$query['token']}' LIMIT 1";
		echo pg_num_rows($result_select_cookie);
		$result_select_cookie = pg_query($query_cookie) or die('Query failed: ' . pg_last_error());
		if (pg_num_rows($result_select_cookie)) {
			$row = pg_fetch_row($result_select_cookie);
			$user_id = $row[0];
			$timeout = $row[1];
			if ($now < $timeout) {
				$query_login = "SELECT name FROM person WHERE user_id = '$user_id' AND is_activated = '1' LIMIT 1";
				$result_login = pg_query($query_login) or die('Query failed1: ' . pg_last_error());
				if ($result_login) {
					$row_login = pg_fetch_row($result_login);
					$name = $row_login[0];
					$_SESSION['username'] = $name;
					$_SESSION['user_id'] = $user_id;
					header('Location: user.php');
					die();
				}
			}
		}
	}
	include_once("close_connection.php");
}

if (!empty($_POST['login_submit'])) {
	include("open_connection.php");
	$email = trim($_POST['email']);
	$email = strtolower($email);
	$password = strip_tags($_POST['password']);
	$remember = false;

	$salt = "F3#@$%ewgSDGaskjf#@$EFsdFGqwjfqad@#$^$%&segjlkszflijs";
	$password = hash('sha256', $salt.$password);

	$query_login = "SELECT email, password, user_id, name, is_admin FROM person WHERE email = '$email' AND is_activated = '1' LIMIT 1";

	if (!empty($email) && !empty($password)) {
		if (!preg_match('/[^A-Za-z0-9\@.]/', $email)) {
			$result_login = pg_query($query_login) or die('Query failed1: ' . pg_last_error());
			if ($result_login) {
				$row = pg_fetch_row($result_login);
				$db_email = $row[0];
				$db_password = $row[1];
				$db_user_id = $row[2];
				$db_username= $row[3];
				$db_is_admin= $row[4];

				if ($email == $db_email && $password == $db_password) {
					$_SESSION['username'] = $db_username;
					$_SESSION['user_id'] = $db_user_id;
					if ($_POST['remember_me'] == '1') {
						$salt = "askhd@!sadknsa!@$R%$*&)(*_GFJsjhfj$WETkahfliqjafloaijfi;oeajfo;k";
						$identifier = hash('sha256', $salt.$db_email);
						$key = md5(uniqid(rand(), true));
//$timeout = time() + 604800; // 7 days
						$timeout = new DateTime('+7 day');
						$timeout =$timeout->format('Y-m-d H:i:s');
						$query_insert_cookie = "INSERT INTO cookie (user_id, identifier, key, timeout) VALUES ('$db_user_id', '$identifier', '$key', '$timeout')";
						$timeout = strtotime($timeout);

						$result_insert_cookie = pg_query($query_insert_cookie) or die('Query failed: ' . pg_last_error());;
						if ($result_insert_cookie) {
							setcookie('user', "$identifier:$key", $timeout);
						}
					}
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
													<input type="hidden" name="remember_me" value="0"/>
													<input type="checkbox" id="input_remember_me" name="remember_me" value="1"/>Remember me
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

	<div class="container-fluid bg-1 text-center">
		<h3>Welcome to crowd funding</h3>
		<img src="img/icon.jpg" class="img-circle" alt="Bird">
	</div>

	<div class="container-fluid bg-2 text-center">
		<h3>Our goal</h3>
		<p>To bring innovative ideas to the real world</p>
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