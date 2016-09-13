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

  if (!empty($_POST['login_submit'])) {
  	include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';
  	$_SESSION['timeout'] = time() + 1800;
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
  					$_SESSION['is_admin'] = $db_is_admin;
  					$_SESSION['timeout'] = time();
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

if (!empty($_POST['sign_up_submit'])) {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';
	$_SESSION['timeout'] = time() + 1800;
	date_default_timezone_set("Asia/Singapore");
	$today_date = date('Y-m-d');
	$error_msg = '';
	$name = trim($_POST['name']);
	$email = trim($_POST['email']);
	$email = strtolower($email);
	$password = strip_tags($_POST['password']);
	$confirm_password = strip_tags($_POST['confirm_password']);
	$birthday = trim($_POST['date']);
	$gender = strip_tags($_POST['gender']);

	$salt = "F3#@$%ewgSDGaskjf#@$EFsdFGqwjfqad@#$^$%&segjlkszflijs";
	$hash_password = hash('sha256', $salt.$password);

	$query_insert_user = "INSERT INTO person (name, email, password, birthday, join_date, gender, is_admin, is_activated) VALUES ('$name', '$email', '$hash_password', '$birthday', '$today_date', '$gender', 'FALSE', 'TRUE')";
	$query_select_duplicate_user = "SELECT email FROM person WHERE email = '$email' LIMIT 1";
	$query_select_user = "SELECT user_id, name FROM person WHERE email = '$email' AND is_activated = '1' LIMIT 1";

	if (!empty($name)) {
		if (!preg_match('/[^A-Za-z0-9]/', $name)) {
			if (!empty($email)) {
				$result_select_duplicate_user = pg_query($query_select_duplicate_user) or die('Query failed: ' . pg_last_error());

				if ($result_select_duplicate_user) {
					$row = pg_fetch_row($result_select_duplicate_user);
					$db_email = $row[0];
				}

				if ($email != $db_email) {
					if (!preg_match('/[^A-Za-z0-9\@.]/', $email)) {
						if (!empty($password)) {
							if (strlen($password) >= 8 && preg_match('/[A-Z]/', $password)) {
								if (!empty($confirm_password)) {
									if ($password !== $confirm_password) {
										$error_msg = 'Password mismatch. Please verify.';
									} else {
										if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$birthday) && $birthday <= $today_date) {
											if (strcmp($gender, 'male') == 0 || strcmp($gender, 'female') == 0) {
												$result_insert_user = pg_query($query_insert_user) or die('Query failed: ' . pg_last_error());
												if ($result_insert_user) {
													$result_select_user = pg_query($query_select_user) or die('Query failed: ' . pg_last_error());
													if ($result_select_user) {
														$row = pg_fetch_row($result_select_user);
														$db_user_id = $row[0];
														$db_username= $row[1];
														$_SESSION['username'] = $db_username;
														$_SESSION['user_id'] = $db_user_id;
														header('Location: /crowd_funding/member/user/user.php');
														die();
													}
												}
											} else {
												$error_msg = 'Please specify your gender.';
											}
										} else {
											$error_msg = 'Invalid birthday. Please try again.';
										}

									}
								} else {
									$error_msg = 'Please enter confirm password';
								}
							} else {
								$error_msg = 'Password minimum length should be 8 characters with at least 1 uppercase.';
							}
						} else {
							$error_msg = 'Please enter your password.';
						}
					} else {
						$error_msg = 'You entered an invalid name with special characters (e.g. \'!\', \'$\', \'#\'.). Please omit them and try again.';
					}
				} else {
					$error_msg = 'This account has already existed. Please try agian.';
				}
			} else {
				$error_msg = 'Please enter your email account.';
			}
		} else {
			$error_msg = 'You entered an invalid name with special characters (e.g. \'!\', \'$\', \'#\'.). Please omit them and try again.';
		}

	} else {
		$error_msg = 'Please enter your name.';
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
	<link rel="stylesheet" type="text.css" href="style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

	<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/header/navbar.php'); ?>

	<div class="container">

		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form-signin">
			<h2 class="form-signin-heading">New Account</h2>
			<label for="input_name" class="sr-only">Name</label>
			<input type="name" id="input_name" class="form-control" placeholder="Name" name="name" required required autofocus>
			<label for="input_email" class="sr-only">Email address</label>
			<input type="email" id="input_email" class="form-control" placeholder="Email address" name="email">
			<label for="input_password" class="sr-only">Password</label>
			<input type="password" id="input_password" class="form-control" placeholder="Password" name="password" required>
			<label for="input_confirm_password" class="sr-only">Confirm Password</label>
			<input type="password" id="input_confirm_password" class="form-control" placeholder="Re-enter Password" name="confirm_password"required>

			<label class="control-label requiredField" for="date">Birthday</label>
			<div class="input-group">
				<div class="input-group-addon">
					<i class="fa fa-calendar">
					</i>
				</div>
				<input class="form-control" id="date" name="date" placeholder="YYYY-MM-DD" type="text"/>
			</div>

			<label class="control-label" for="select1">Gender</label>
			<select class="select form-control" id="select1" name="gender">
				<option value="-">-</option>
				<option value="male">male</option>
				<option value="female">female</option>
			</select>
			<font size="3" color="red"><?php echo $error_msg; ?></font>
			<br><br>


			<button class="btn btn-lg btn-primary btn-block" type="submit" name="sign_up_submit" value="sign_up_submit">Sign Up</button>
		</form>

	</div> <!-- /container -->

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>

</body>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/header/footer.php'); ?>
</footer>
</html>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

<!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

<script>
	$(document).ready(function(){
  var date_input=$('input[name="date"]'); //our date input has the name "date"
  var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
  date_input.datepicker({
  	format: 'yyyy-mm-dd',
  	container: container,
  	todayHighlight: true,
  	autoclose: true,
  })
})
</script>