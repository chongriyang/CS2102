 <?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';

if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
} else {	
	header('Location: /crowd_funding/index.php');
die();
}

if(isset($_GET['id']) && isset($_GET['user_id']))
{
	$url=$_SERVER['REQUEST_URI'];
	$_SESSION['url'] = $url;
	$id=$_GET['id'];
	$user_id=$_GET['user_id'];
	$_SESSION['edit_project_id'] = $id;
	$_SESSION['edit_project_user_id'] = $user_id;
}

$id3 = $_SESSION['edit_project_id'];
$user_id=$_SESSION['edit_project_user_id'];
$query = "SELECT * FROM project p WHERE p.project_id='$id3'";
$query1=pg_query($query) or die('Query failed: edit' . pg_last_error());
$query2=pg_fetch_array($query1);

$editStartDate= date('m/d/Y',strtotime($query2['start_date']));
$editEndDate= date('m/d/Y',strtotime($query2['end_date']));

$category_id = $query2['category_id'];
$getCategoryType = pg_query("SELECT c.type FROM category c WHERE c.category_id = '$category_id'") or die ('Query failed' . pg_last_error());
$row3 = pg_fetch_array($getCategoryType);

if (isset($_POST['edit_project'])) {
	$id2 = $id3;
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

	$category_type = $_POST['type'];
	$getCategoryID = pg_query("SELECT c.category_id FROM category c WHERE c.type = '$category_type'") or die ('Query failed' . pg_last_error());
	$row4 = pg_fetch_array($getCategoryID);
	$categoryID = $row4['category_id'];

	$query_update_project = "UPDATE project SET  name='$name', category_id = $categoryID, description='$description', start_date='$startDate', end_date='$endDate', amount='$amount' WHERE project_id='$id2' AND user_id='$user_id' ";
	$query_select_duplicate_project = "SELECT p.name, p.user_id FROM project p WHERE p.name = '$name' AND p.user_id = '$user_id' LIMIT 1";
	$query_select_user = "SELECT p.user_id, p.name FROM person p WHERE p.email = '$email' AND p.is_activated = '1' LIMIT 1";
			
		if(strcmp($name,$query2['name'])!==0 ){
			
			$result_select_duplicate_project = pg_query($query_select_duplicate_project) or die('Query failed: duplicate2' . pg_last_error());
			if ($result_select_duplicate_project) {
				$row = pg_fetch_row($result_select_duplicate_project);
				$db_name = $row[0];
				$db_user = $row[1];
			}
		
		}
		if($name != $db_name && $user_id != $db_user){
			if (str_word_count($description)<=100) {
				$result_update_project = pg_query($query_update_project) or die('Query failed: insert ' . pg_last_error());
				if ($result_update_project) {\
						header('Location: /crowd_funding/member/administrator/manage_project.php');
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
  
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Edit Project</title>
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
			<h2 class="form-signin-heading">Edit Project</h2>
			<label for="input_name" class="control-label">Name</label>
			<input type="name" id="input_name" class="form-control" placeholder="Name" name="name" value="<?php echo $query2['name']; ?>" required required autofocus>
			<label for="input_name" class="control-label">Category</label>
			<div class="form-group">
				<!-- <input type="text" class="form-control" name="type" placeholder="category"> -->
				<select class="select form-control" name="type" placeholder="category">
					<option value=<?php echo $row3['type'];?>><?php echo $row3['type'];?></option>
					<option value="Art">Art</option>
					<option value="Comics">Comics</option>
					<option value="Crafts">Crafts</option>
					<option value="Dance">Dance</option>
					<option value="Design">Design</option>
					<option value="Fashion">Fashion</option>
					<option value="Film & Video">Film & Video</option>
					<option value="Food">Food</option>
					<option value="Games">Games</option>
					<option value="Journalism">Journalism</option>
					<option value="Music">Music</option>
					<option value="Photography">Photography</option>
					<option value="Publishing">Publishing</option>
					<option value="Technology">Technology</option>
					<option value="Theater">Theater</option>
				</select>
			</div>

			<div class="form-group">
			  <label for="comment">Description:</label>
			  <textarea class="form-control" name="description" placeholder="Enter up to 100 words" rows="5" id="comment" required required autofocus><?php echo $query2['description']; ?></textarea>
			</div>


			<div class="form-group">
			<label for="comment">Start Date - End Date</label>
			<input type="text" name="daterange" value="<?php echo $editStartDate. " - " . $editEndDate?>" class="form-control" />
			</div>
			<script type="text/javascript">
			$(function() {
				$('input[name="daterange"]').daterangepicker();
			});
			</script>

			<div class="form-group">
			  <label for="amount">Amount: $</label>
			  <input type="text" name="amount" class="form-control" id="amount" value="<?php echo $query2['amount']; ?>"required required autofocus>
			</div>

			<font size="3" color="red"><?php echo $error_msg; ?></font>
			<br><br>


			<button class="btn btn-lg btn-primary btn-block" type="submit" name="edit_project" value="edit_project" id="submit">Edit Project</button>
		
		</form>
	<?php
		
	 ?>
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