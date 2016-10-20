<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
	$username = $_SESSION['username'];
	$user_id = $_SESSION['user_id'];
} else {
	header('Location: /crowd_funding/index.php');
die();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';


?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>My Projects</title>
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
		
		echo "<table><tr><td>Project ID</td><td>Owner</td><td>Project Name</td><td>Description</td><td>Category</td><td>Target Amount</td><td>Raised</td><td>Start Date</td><td>Closing Date</td><td>No. of contributors</td><td colspan='4'><center>Action</center></td>";
		$results = pg_query("SELECT p1.project_id as project_id, u1.name as user_name, c1.type as category_type, p1.name as project_name,
			p1.description as description, p1.amount as amount, p1.start_date as start_date, p1.end_date as end_date, SUM(t1.amount) as raised, 
			COUNT(DISTINCT t1.user_id) as number_of_contributor FROM person u1, project p1, transaction t1, category c1 WHERE p1.user_id=u1.user_id 
			AND p1.project_id=t1.project_id AND p1.category_id=c1.category_id AND u1.user_id=$user_id GROUP BY p1.project_id, p1.user_id, p1.name, p1.description, p1.amount, 
			p1.start_date, p1.end_date, u1.name, c1.category_id, c1.type UNION SELECT p2.project_id as project_id, u2.name as user_name, c2.type as category_type, 
			p2.name as project_name,p2.description as description, p2.amount as amount, p2.start_date as start_date, p2.end_date as end_date, 0 as raised, 0 as number_of_contributor 
			FROM person u2, project p2, category c2 WHERE p2.user_id=u2.user_id AND p2.category_id=c2.category_id AND u2.user_id=$user_id
			AND NOT EXISTS (SELECT * FROM transaction t2 WHERE p2.project_id = t2.project_id)") or die('Query failed: insert ' . pg_last_error());
		
		while($query2=pg_fetch_array($results))
		{
			$project_id=$query2['project_id'];
			$query_bookmark = "SELECT FROM bookmark where project_id='$project_id' AND user_id='$user_id'";
			$result=pg_query($query_bookmark);
			
			if(pg_num_rows($result)>0){
				$bookmark=true;
				$printBookmark='Unbookmark';
				$img='bookmark';
			}
			else{
				$bookmark=false;
				$printBookmark='Bookmark';
				$img='unbookmark';
			}
			
			$projectID = $query2['project_id'];
			$categoryID = $query2['category_id'];
			$projectName = $query2['project_name'];
			$description = $query2['description'];
			$amount = $query2['amount'];
			$number_of_contributor = $query2['number_of_contributor'];
			$raised = $query2['raised'];
			$startDate = $query2['start_date'];
			$endDate = $query2['end_date'];
			$username = $query2['user_name'];
			$categorytype = $query2['category_type'];

			echo "<tr>";
			echo "<td>$projectID</td>";
			echo "<td>$username</td>";
			echo "<td>$projectName</td>";
			echo "<td>$description</td>";
			echo "<td>$categorytype</td>";
			echo "<td>$amount</td>";
			echo "<td>$raised</td>";
			echo "<td>$startDate</td>";
			echo "<td>$endDate</td>";
			echo "<td>$number_of_contributor</td>";
			echo "<td</td>";
			echo "<td width=40><a class=".$img." href='/crowd_funding/project/bookmark_project.php?id=".$query2['project_id']."&bookmark=".$bookmark."' id='unbookmark'>"."</a></td>";
			
			?><td width=70><button  type="button"  class="btn btn-success"  data-toggle="modal" data-target="#<?php echo''.$project_id.'';?>">Fund</button></td>
			<?php 
			
			echo "<td><a class='btn btn-info' href='/crowd_funding/project/edit_project.php?id=".$query2['project_id']."'>Edit</a></td>";
			echo "<td width=70><a class='btn btn-danger' href='/crowd_funding/project/delete_project.php?id=".$query2['project_id']."'>Delete</a></td></tr>";
			
			?>					
			<div class="modal fade" id="<?php echo''.$project_id.'';?>">
				<div class="modal-dialog">
					<div class="modal-content">

						<!-- header -->
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h3 class="modal-title">FUND AMOUNT</h3>
						</div>

						<!-- body (form) -->
						<div class="modal-body">
							<form method="get" action="/crowd_funding/project/fund_project.php" class="form-signin">
							
								<label for="input_amount" class="sr-only">Amount: </label>
								<input type="text" id="input_amount" class="form-control" placeholder="Enter amount" name="amount" required autofocus>
							
							
								<button class="btn btn-lg btn-primary btn-block" type="submit" name="project_id" value="<?php echo''.$project_id.'';?>">Fund</button>
							
							</form>
						</div>
						
					</div>
				</div>
			</div>
								
			
		<?php
		}
		?>
		</ol>
		</table>       

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
	<style>
	.unbookmark {
        background-color: transparent;
        background-image: url('http://localhost/crowd_funding/img/unbookmark.png');
        background-repeat:no-repeat;
        display: block;  
        height:30px;
        width:30px;
        float:center;
    }   
	</style>
	<style>
	.bookmark {
        background-color: transparent;
        background-image: url('http://localhost/crowd_funding/img/bookmark.png');
        background-repeat:no-repeat;
        display: block;  
        height:30px;
        width:30px;
        float:center;
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
