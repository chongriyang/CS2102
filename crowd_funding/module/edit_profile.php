`  <?php
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

  if (!empty($_POST['edit_profile_submit'])) {
  	include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';
  	$_SESSION['timeout'] = time() + 1800;
  	date_default_timezone_set("Asia/Singapore");
  	$today_date = date('Y-m-d');
  	$error_msg = '';
  	$user_id = $_SESSION['user_id'];
  	$name = trim($_POST['name']);
  	$email = trim($_POST['email']);
  	$email = strtolower($email);
  	$password = strip_tags($_POST['password']);
  	$birthday = trim($_POST['date']);
  	$gender = strip_tags($_POST['gender']);

  	$salt = "F3#@$%ewgSDGaskjf#@$EFsdFGqwjfqad@#$^$%&segjlkszflijs";
  	$hash_password = hash('sha256', $salt.$password);

  	$query_edit_user = "";

  	if ($password == "") {
  		$query_edit_user = "UPDATE person SET name='$name', email='$email', birthday='$birthday', gender='$gender' WHERE user_id='$user_id'";
  	} else {
  		$query_edit_user = "UPDATE person SET name='$name', email='$email', password='$hash_password', birthday='$birthday', gender='$gender' WHERE user_id='$user_id'";
  	}

  	if (!empty($name)) {
  		if (!preg_match('/[^A-Za-z0-9]/', $name)) {
  			if (!empty($email)) {

  				if (!preg_match('/[^A-Za-z0-9\@.]/', $email)) {
  					$valid_password = true;
  					if (!empty($password)) {
  						if (strlen($password) >= 8 && preg_match('/[A-Z]/', $password)) {
  							$valid_password = true;
  						} else {
  							$valid_password = false;
  						}
  					}
  					if ($valid_password) {
  						if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$birthday) && $birthday <= $today_date) {
  							if (strcmp($gender, 'male') == 0 || strcmp($gender, 'female') == 0) {
  								$result_edit_user = pg_query($query_edit_user) or die('Query failed: ' . pg_last_error());
  								if ($result_edit_user) {
  									$_SESSION['username'] = $name;
  									header('Location: /crowd_funding/module/edit_profile.php');
  									die();
  								}
  							} else {
  								$error_msg = 'Please specify your gender.';
  							}
  						} else {
  							$error_msg = 'Invalid birthday. Please try again.';
  						}
  					} else {
  					$error_msg = 'Password minimum length should be 8 characters with at least 1 uppercase.';
  				}
  			} else {
  				$error_msg = 'You entered an invalid name with special characters (e.g. \'!\', \'$\', \'#\'.). Please omit them and try again.';
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
	<?php
	include $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';
	$user_id = $_SESSION['user_id'];
	$query = "SELECT * FROM person p WHERE p.user_id='$user_id' LIMIT 1";
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	$user = pg_fetch_array($result, null, PGSQL_ASSOC);
	include $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/close_connection.php';
	?>

	<div class="container">

		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form-signin">
			<h2 class="form-signin-heading">Edit Profile</h2>
			<label for="input_name" class="sr-only">Name</label>
			<input type="name" id="input_name" class="form-control" placeholder="Name" name="name" value=<?php echo $user['name']; ?> required required autofocus>
			<label for="input_email" class="sr-only">Email address</label>
			<input type="email" id="input_email" class="form-control" placeholder="Email address" name="email" value=<?php echo $user['email']; ?>>
			<label for="input_password" class="sr-only">Password</label>
			<input type="password" id="input_password" class="form-control" placeholder="New Password" name="password">

			<label class="control-label requiredField" for="date">Birthday</label>
			<div class="input-group">
				<div class="input-group-addon">
					<i class="fa fa-calendar">
					</i>
				</div>
				<input class="form-control" id="date" name="date" placeholder="YYYY-MM-DD" value=<?php echo $user['birthday']; ?> type="text"/>
			</div>

			<label class="control-label" for="select1">Gender</label>
			<select class="select form-control" id="select1" name="gender">
				<option value=<?php echo $user['gender']; ?>><?php echo $user['gender']; ?></option>
				<option value="male">male</option>
				<option value="female">female</option>
			</select>
			<font size="3" color="red"><?php echo $error_msg; ?></font>
			<br><br>

			<button class="btn btn-lg btn-primary btn-block" type="submit" name="edit_profile_submit" value="edit_profile_submit">Edit</button>
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