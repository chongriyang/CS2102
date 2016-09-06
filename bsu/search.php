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

if (!empty($_POST['login_submit'])) {
include_once("open_connection.php");
$email = trim($_POST['email']);
$email = strtolower($email);
$password = strip_tags($_POST['password']);

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
echo $db_password;
echo $db_is_admin;
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

if (!empty($_POST['sign_up_submit'])) {
include_once("open_connection.php");
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
$query_insert_user = "INSERT INTO person (name, email, password, birthday, join_date, gender, is_admin, is_activated) VALUES ('$name', '$email', '$password', '$birthday', '$today_date', '$gender', 'FALSE', 'TRUE')";
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
                      header('Location: user.php');
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
<?php if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) { ?>
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
<?php } ?>
<?php if (!(isset($_SESSION['username']) && isset($_SESSION['user_id']))) { ?>
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
<input type="checkbox" value="remember_me" name="remember_me">Remember me
</label>
</div>
<button class="btn btn-lg btn-primary btn-block" type="submit" name="login_submit" value="login_submit">Sign in</button>
</form>
</div>
<?php } ?>
</div>
</div>
</div>
</li>

</ul>
</div>
</div>
</nav>

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
include_once("close_connection.php");
?>


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
