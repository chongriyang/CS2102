<html>
<body>
<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['user_id']) && isset($_SESSION['url'])) {
  	$username = $_SESSION['username'];
  	$user_id = $_SESSION['user_id'];
	$url = $_SESSION['url'];
} 
else {
  	header('Location: /crowd_funding/index.php');
  	die();
}
include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';
if(isset($_GET['id'])&&isset($_GET['bookmark']))
{
	$project_id=$_GET['id'];
	$bookmark=$_GET['bookmark'];

	$query_bookmark="INSERT INTO bookmark (user_id,project_id) VALUES ('$user_id','$project_id')";
	$query_unbookmark="delete from bookmark where project_id='$project_id' AND user_id='$user_id'";

	if($bookmark==''){
		pg_query($query_bookmark)or die('Query failed:' . pg_last_error());
		header('location:'.$url);
	}
	else{
		pg_query($query_unbookmark)or die('Query failed:' . pg_last_error());
		header('location:'.$url);
	}

}
?>
</body>
</html>