  <?php
  error_reporting(E_ALL & ~E_NOTICE);
  session_start();
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
<input type="checkbox" value="remember_me" name="remember_me">Remember me
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


<footer class="container-fluid bg-4 text-center">
<p><a href="about_us.php">About Us</a></p>
</footer>
</html>
