<?php

error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
	$username = $_SESSION['username'];
  	$user_id = $_SESSION['user_id'];
}
else {
  	header('Location: /crowd_funding/index.php');
  	die();
}

if (!empty($_POST['create_project'])) {
	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';
	
	date_default_timezone_set("Asia/Singapore");
	
	$today_date = date('Y-m-d');
	$error_msg = '';
	$name = trim($_POST['name']);
	$description = trim($_POST['description']);
	$dateStr = trim($_POST['daterange']);
	$amount = trim($_POST['amount']);
	
	$arr = explode(' ',trim($dateStr));
	$start=$arr[0];
	$end=$arr[2];
    $time = strtotime($start);
	$time2 = strtotime($end);
	$startDate = date('Y-m-d',$time);
	$endDate = date('Y-m-d',$time2);

	$query_insert_project = "INSERT INTO project (user_id, name, description, start_date, end_date, amount, raised) VALUES ('$user_id', '$name', '$description', '$startDate', '$endDate', '$amount', '0.0')";
	$query_select_duplicate_project = "SELECT name,user_id FROM project WHERE name = '$name' AND user_id = '$user_id' LIMIT 1";
	$query_select_user = "SELECT user_id, name FROM person WHERE email = '$email' AND is_activated = '1' LIMIT 1";
		
	if (!preg_match('/[^A-Za-z0-9 ]/', $name)) {
		$result_select_duplicate_project = pg_query($query_select_duplicate_project) or die('Query failed: duplicate2' . pg_last_error());
		if ($result_select_duplicate_project) {
			$row = pg_fetch_row($result_select_duplicate_project);
			$db_name = $row[0];
			$db_user = $row[1];
		}
		if($name != $db_name && $user_id != $db_user){
			if (str_word_count($description)<=100) {
				$result_insert_project = pg_query($query_insert_project) or die('Query failed: insert ' . pg_last_error());
				
				if ($result_insert_project) {
					$_SESSION['username'] = $username;
					$_SESSION['user_id'] = $user_id;
					header('Location: /crowd_funding/project/view_project.php');
					die();
                    
                }
			}
			else{
				$error_msg = 'You have exceeded 100 word count for description field.';
			}
		}
		else{
			$error_msg = 'This project has already existed. Please try agian.';
		}
				
	}
	else {
		$error_msg = 'You entered an invalid name with special characters (e.g. \'!\', \'$\', \'#\'.). Please omit them and try again.';
	}
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
	  
		<!-- Include Required Prerequisites -->
		<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
		<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
		<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/latest/css/bootstrap.css" />
  
  
	</head>
	<body>

		<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/header/navbar.php'); ?>


		<div class="container">

		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form-signin">
			<h2 class="form-signin-heading">Create Project</h2>
			<label for="input_name" class="sr-only">Name</label>
			<input type="name" id="input_name" class="form-control" placeholder="Name" name="name" required required autofocus>

			<div class="form-group">
			  <label for="comment">Description:</label>
			  <textarea class="form-control" name="description" placeholder="Enter up to 100 words" rows="5" id="comment" required required autofocus></textarea>
			</div>


			<div class="form-group">
			<label for="comment">Start Date - End Date</label>
			<input type="text" name="daterange" value="01/01/2015 - 01/31/2015" class="form-control" />
			</div>
			<script type="text/javascript">
			$(function() {
				$('input[name="daterange"]').daterangepicker();
			});
			</script>

			<div class="form-group">
			  <label for="amount">Amount: $</label>
			  <input type="text" name="amount" class="form-control" id="amount" required required autofocus>
			</div>

			<font size="3" color="red"><?php echo $error_msg; ?></font>
			<br><br>


			<button class="btn btn-lg btn-primary btn-block" type="submit" name="create_project" value="create_project">Create Project</button>
		</form>

		</div> <!-- /container -->


		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>

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

<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>


<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

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