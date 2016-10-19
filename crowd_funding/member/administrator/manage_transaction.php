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
	<table class="table table-bordered table-striped">
	<link href="select2-bootstrap.css" rel="stylesheet" type="text/css"></link>
	<thead>
		<tr>
			<th class="">Transaction no.</th>
			<th class="">User</th>
			<th class="">Email</th>
			<th class="">Project Name</th>
			<th class="">Amount</th>
			<th class="">Date</th>
			<th class="">time</th>
			<th class="">Edit</th>
			<th class="">Delete</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$_SESSION['timeout'] = time() + 1800;
		require_once($_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/header/navbar.php'); 
		
		$url=$_SERVER['REQUEST_URI'];
		$_SESSION['url'] = $url;
		
		$results = pg_query("SELECT t.transaction_id as transaction_id, u.name as user_name, u.email as email, p.name as project_name,t.amount as amount,t.date_time as date_time FROM project p,transaction t, person u WHERE t.project_id=p.project_id and t.user_id=u.user_id");
		
		echo '<div class="container">'; 
		echo '<div class="row">';
		while($query2=pg_fetch_array($results))
		{
			$transaction_id=$query2['transaction_id'];
			$date_time = $query2['date_time'];
			
			$parts = explode(" ", $date_time);
			$date = $parts[0];
			$time = $parts[1];
			
			echo "<tr><td>".$query2['transaction_id']."</td>";
			echo "<td>".$query2['user_name']."</td>";
			echo "<td>".$query2['email']."</td>";
			echo "<td>".$query2['project_name']."</td>";
			echo "<td>".$query2['amount']."</td>";
			echo "<td>".$date."</td>";
			echo "<td>".$time."</td>";
			?>

	<td width=70><button  type="button"  class="btn btn-success"  data-toggle="modal" data-target="#<?php echo''.$query2['transaction_id'].'';?>">Edit</button></td>
		<div class="modal fade" id="<?php echo''.$query2['transaction_id'].'';?>">
			<div class="modal-dialog">
				<div class="modal-content">

					<!-- header -->
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h3 class="modal-title"></h3>
					</div>

					<!-- body (form) -->
					<div class="modal-body">
						<form method="get" action="/crowd_funding/member/administrator/edit_transaction.php" class="form-signin">
							<h2 class="form-signin-heading">Edit Transaction ID: <?php echo $query2['transaction_id'];?></h2>
							<h3 class="form-signin-heading">User: <?php echo $query2['user_name'];?></h3>
							<h3 class="form-signin-heading">Email: <?php echo $query2['email'];?></h3>
							<h3 class="form-signin-heading">Project: <?php echo $query2['project_name'];?></h3>
							<label for="amount" class="control-label">Amount</label>
							<input type="name" id="amoubt" class="form-control" placeholder="Amount" name="amount" value=<?php echo $query2['amount'];?> required required autofocus>
							<br><br>
							<button class="btn btn-lg btn-primary btn-block" type="submit" name="edit_transaction" value="<?php echo''.$query2['transaction_id'].'';?>">Edit</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
			echo "<td width=70><a class='btn btn-danger' href='/crowd_funding/member/administrator/delete_transaction.php?id=".$query2['transaction_id']."'>Delete</a></td></tr>";
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