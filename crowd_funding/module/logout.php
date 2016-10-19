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

	if (isset($_COOKIE['user']) && !empty(isset($_COOKIE['user']))) {
		include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';
		$_SESSION['timeout'] = time() + 1800;
		setcookie('user', "", time() - 3600, "/", "", 0);
		$user_id = $_SESSION['user_id'];
		$query_select_user = "SELECT c.user_id FROM cookie c WHERE c.user_id = '$user_id' LIMIT 1";
		$result_select_user = pg_query($query_select_user) or die('Query failed: ' . pg_last_error());
		if ($result_select_user) {
			$query_delete_cookie = "DELETE FROM cookie c WHERE c.user_id = '$user_id'";
			$result_delete_cookie = pg_query($query_delete_cookie) or die('Query failed: ' . pg_last_error());
			if ($result_delete_cookie) {
		//Successfully delete entry from cookie table
			}
		}
		session_destroy();
		include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/close_connection.php';
	}

	$_SESSION['username'] = null;
	$_SESSION['user_id'] = null;
	$_SESSION['is_admin'] = null;
	$_SESSION['timeout'] = time()+1800;

	$username = null;
	$user_id = null;
	$is_admin = null;

	session_start();

if (!empty($_POST['login_submit'])) {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';
	$_SESSION['timeout'] = time() + 1800;
	$email = trim($_POST['email']);
	$email = strtolower($email);
	$password = strip_tags($_POST['password']);
	$remember = false;

	$salt = "F3#@$%ewgSDGaskjf#@$EFsdFGqwjfqad@#$^$%&segjlkszflijs";
	$password = hash('sha256', $salt.$password);

	$query_login = "SELECT email, password, user_id, name, is_admin, is_activated FROM person WHERE email = '$email' LIMIT 1";

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
				$db_is_activated = $row[5];

				if ($email == $db_email && $password == $db_password) {
					if ($db_is_activated === 't') {
						$_SESSION['username'] = $db_username;	
						$_SESSION['user_id'] = $db_user_id;
						$_SESSION['is_admin'] = $db_is_admin;
						if ($_POST['remember_me'] == '1') {
							$salt = "askhd@!sadknsa!@$R%$*&)(*_GFJsjhfj$WETkahfliqjafloaijfi;oeajfo;k";
							$identifier = hash('sha256', $salt.$db_email);
						$key = md5(uniqid(rand(), true)); //$timeout = time() + 604800; // 7 days
						$timeout = new DateTime('+7 day');
						$timeout =$timeout->format('Y-m-d H:i:s');
						$query_existing_cookie = "SELECT p.user_id, c.timeout FROM person p, cookie c WHERE p.user_id = c.user_id AND p.email = '$db_email'";

						$result_select_cookie = pg_query($query_existing_cookie) or die('Query failed2: ' . pg_last_error());
						if (pg_num_rows($result_select_cookie)) {

							$query_update_cookie = "UPDATE cookie SET key = '$key', timeout = '$timeout' WHERE user_id = '$db_user_id'";
							$result_update_cookie = pg_query($query_update_cookie) or die('Query failed1: ' . pg_last_error());;
							
							if ($result_update_cookie) {
								$timeout = strtotime($timeout);
								setcookie('user', "$identifier:$key", $timeout, "/");
							}
						} else {

							$query_insert_cookie = "INSERT INTO cookie (user_id, identifier, key, timeout) VALUES ('$db_user_id', '$identifier', '$key', '$timeout')";
							$result_insert_cookie = pg_query($query_insert_cookie) or die('Query failed:3 ' . pg_last_error());;
							if ($result_insert_cookie) {
								$timeout = strtotime($timeout);
								setcookie('user', "$identifier:$key", $timeout, "/");
							}
						}
					}
					if ($db_is_admin === 't') {
						header('Location: /crowd_funding/member/administrator/administrator.php');
						die();
					} else {
						header('Location: /crowd_funding/member/user/user.php');
						die();
					}
				} else {
					echo "Your account has been deactivated. Please contact the administrator.";
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
include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/close_connection.php';
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

	<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/header/logout_success_1.php'); ?>

</body>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/header/footer.php'); ?>
</html>