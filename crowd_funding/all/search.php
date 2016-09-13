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
	<?php require_once($_SERVER['DOCUMENT_ROOT'].'/crowd_funding/header/navbar.php'); ?>
			<!-- Search Section -->
			<section id="search_bar">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 text-center">
							<h3>Search bar</h3>
						</div>
					</div>
				</div>

				<!-- Search form template -->
				<div class="container">
					<div class="row">
						<div class="col-lg-12 text-center">
							<form class="form-inline" method="post">
								<div class="form-group">
									<input type="text" class="form-control" name="name" placeholder="project title">
								</div>
								<div class="form-group">
									<input type="text" class="form-control" name="description" placeholder="description">
								</div>
								<div class="form-group">
									<input type="number" class="form-control" name="amount" placeholder="pledged amount">
								</div>
								<div class="form-group">
									<input type="number" class="form-control" name="raised" placeholder="raised amount">
								</div>
								<button type="submit" name="formSubmit" value="Search" class="btn btn-primary">Search</button>
							</form>
						</div>
					</div>
				</div>
			</section>
		</body>



		<?php 
/************************
 *establish db connection
 ************************/	
include_once("open_connection.php");

/**********************************************************
 *check if form is submitted, if yes, start SQL query
 **********************************************************/	
if(isset($_POST['formSubmit'])) 
{
	$fields = array('name', 'description', 'amount', 'raised');
	$conditions = array();
	$query = '';
	
	/*********************************
	 *get all search keywords, if any
	 *********************************/	
	foreach($fields as $value){

		if($_POST[$value] != '') {

			$query = "SELECT * FROM project ";
			//doing case insensitive search on strings and strict comparison on numbers
			if(is_numeric($_POST[$value])){
				$conditions[] = "$value = " . pg_escape_string($_POST[$value]) . " ";
			}else{
				$searchTerm = strtolower($_POST[$value]);
				$conditions[] = "lower($value) LIKE '%" . pg_escape_string($searchTerm) . "%'";
			}
		}
	}

	/**************************************************
	 *append search query if search keywords are found 
	 **************************************************/
	if(count($conditions) > 0) {
		$query .= " WHERE " . implode (' AND ', $conditions); // you can change to 'OR', but I suggest to apply the filters cumulative
		$query .= " ORDER BY project_id ASC ";
	}

	/**************************************************
	 *display SQL query (for testing) 
	 **************************************************/
	echo '<div class="container">'; 
	echo '<div class="row">'; 
	echo '<div class="col-lg-12 text-center">';
	echo "<b>SQL: </b>".$query."<br><br>"; 
	echo "</div>"; 
	echo "</div>"; 
	echo "</div>"; 

	/**************************************************
	 *SQL query validate
	 **************************************************/
	$result = pg_query($query) or die('Please enter at least one search term');


	/**************************************************
	 *get SQL query result count
	 **************************************************/
	$num_rows = pg_num_rows($result);
	echo '<div class="container">'; 
	echo '<div class="row">'; 
	echo '<div class="col-lg-12 text-center">';
	echo "<p><strong>$num_rows </strong> results for '$searchTerm'</p>";
	echo "</div>"; 
	echo "</div>"; 
	echo "</div>"; 

	/**************************************************
	 *display SQL query result (html formatting)
	 **************************************************/
	echo '<div class="container">'; 
	echo '<div class="row">'; 
	while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$projectID = $row['project_id'];
		$name = $row['name'];
		$description = $row['description'];
		$amount = $row['amount'];
		$raised = $row['raised'];
		$endDate = $row['end_date'];

		echo '<table class="table table-hover table-inverse">';
		echo "<thead>";
		echo "<tr>";
		echo "<th>Project ID</th>";
		echo "<th>Project Name</th>";
		echo "<th>Description</th>";
		echo "<th>Total Amount</th>";
		echo "<th>Amount Raised</th>";
		echo "<th>Closing Date</th>";
		echo "<th></th>";
		echo "</tr>";
		echo "</thead>";

		echo("<tbody>");
		echo "<tr>";
		echo "<td>$projectID</td>";
		echo "<td>$name</td>";
		echo "<td>$description</td>";
		echo "<td>$amount</td>";
		echo "<td>$raised</td>";
		echo "<td>$endDate</td>";
		echo "<td><a href='project.php?project_id=$projectID'>View</a></td>";
		echo "</tr><br>";
		echo("</tbody>");
		echo "</table>";
	} 
	echo "</div>"; 
	echo "</div>"; 
	/**************************************************
	 *end html formatting)
	 **************************************************/

	/**************************************************
	 *clsoe DB
	 **************************************************/
	pg_free_result($result);
}
include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/close_connection.php';
?>


<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/header/footer.php'); ?>
</html>
