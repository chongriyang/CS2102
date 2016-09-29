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

include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>My Transactions</title>
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
	<center>
		<?php 
		require_once($_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/header/navbar.php'); 
		
		$url=$_SERVER['REQUEST_URI'];
		$_SESSION['url'] = $url;
		
		echo "<table><tr><td>Transaction no.</td><td>Project name</td><td>Amount</td><td>Date</td><td>Time</td><td colspan='1'><center>Action</center></td>";
		$results = pg_query("SELECT t.transaction_id,p.name,t.amount,t.date_time FROM project p,transaction t WHERE t.project_id=p.project_id AND t.user_id='$user_id'");
		while($query2=pg_fetch_array($results))
		{
			$transaction_id=$query2['transaction_id'];
			$date_time = $query2['date_time'];
			
			$parts = explode(" ", $date_time);
			$date = $parts[0];
			$time = $parts[1];
			
			echo "<tr><td>".$query2['transaction_id']."</td>";
			echo "<td>".$query2['name']."</td>";
			echo "<td>".$query2['amount']."</td>";
			echo "<td>".$date."</td>";
			echo "<td>".$time."</td>";
			echo "<td width=70><a class='btn btn-danger' href='/crowd_funding/project/delete_transaction.php?id=".$query2['transaction_id']."'>Delete</a></td></tr>";
			
		?>
							
								
							
		
		<?php
		}
		?>
		
		     

	   </center>
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
		<p><a href="/crowd_funding/all/about_us.php">About Us</a></p>
	</footer>
</html>
<style type="text/css">
td
{
padding:5px;
border:1px solid #ccc;

}
</style>