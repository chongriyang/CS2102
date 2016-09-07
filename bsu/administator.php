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
					<span class="dropdown"></span> 
				</button>
				<a class="navbar-brand" href="#">CrowdFunding</a>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="#">Browse</a></li>
					<li><a href="#">Create a Project</a></li>
					<li><a href="#">Gallery</a></li>
					<li><a href="search.php">Search</a></li>
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
						</ul>
					</ul>
				</div>
			</div>
		</nav>

		<table class="table table-bordered table-striped">
		<h1>User Accounts</h1>
		<thead>
        <tr>
            <th class="">id</th>
            <th class="">Name</th>
            <th class="">Password</th>
            <th class="">Email</th>
            <th class="">Birthday</th>
            <th class="">Join date</th>
            <th class="">Gender</th>
            <th class="">Priviledge</th>
            <th class="">Status</th>
            <th class="">No. Bookmarks</th>
            <th class=""></th>
        </tr>
    	</thead>
    	<tbody>
		<?php
		include_once("open_connection.php");
		$query_all_users = "SELECT * FROM person";
		$result = pg_query($query_all_users) or die('Query failed: ' . pg_last_error());
		while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$db_user_id = $row['user_id'];
		$db_name = $row['name'];
		$db_password = $row['password'];
		$db_email = $row['email'];
		$db_birthday = $row['birthday'];
		$db_join_date = $row['join_date'];
		$db_gender = $row['gender'];
		$db_is_admin = $row['is_admin'];
		$db_is_activated = $row['is_activated'];
		$db_bookmark = $row['bookmark'];

		if ($db_is_admin == 't') {
			$db_is_admin = "admin";
		}	 else {
			$db_is_admin = "user";
		}

if ($db_is_activated == 't') {
			$db_is_activated = "activated";
		}	 else {
			$db_is_activated = "de-activated";
		}

		?>
        <tr>
            <td style="text-align:left;" class=""><?php echo $db_user_id ?></td>
            <td style="text-align:left;" class=""><?php echo $db_name ?></td>
            <td style="text-align:left;" class=""><?php echo $db_password ?></td>
            <td style="text-align:left;" class=""><?php echo $db_email ?></td>
            <td style="text-align:left;" class=""><?php echo $db_birthday ?></td>
            <td style="text-align:left;" class=""><?php echo $db_join_date ?></td>
            <td style="text-align:left;" class=""><?php echo $db_gender ?></td>
            <td style="text-align:left;" class=""><?php echo $db_is_admin ?></td>
            <td style="text-align:left;" class=""><?php echo $db_is_activated ?></td>
            <td style="text-align:left;" class=""><?php echo $db_bookmark ?></td>
            <td style="text-align:center;">
                <button class="btn btn-success" data-toggle="modal" data-target="#myModal" contenteditable="false">Edit</button>
            </td>
        </tr>
        <?php
    	}
    	include_once("close_connection.php");
        ?>
    </tbody>
</table>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"> <span aria-hidden="true" class="">×   </span><span class="sr-only">Close</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Modal title</h4>

            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
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