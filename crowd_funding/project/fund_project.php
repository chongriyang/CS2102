<html>
<body>
<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['user_id']) && isset($_SESSION['url'])) {

	$username = $_SESSION['username'];
	$user_id = $_SESSION['user_id'];
	$url = $_SESSION['url'];
	
} else {
header('Location: /crowd_funding/index.php');
die();
}
  
include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';
if(isset($_GET['project_id'])&&isset($_GET['amount']))
{
	$project_id=$_GET['project_id'];
	$amount=$_GET['amount'];
	date_default_timezone_set('Asia/Singapore');
	$now = new DateTime();
	$now =$now->format('Y-m-d H:i:s');
	$query1=pg_query("INSERT INTO transaction (user_id,project_id,amount,date_time) VALUES ('$user_id','$project_id','$amount','$now')") or die('Query failed:3 ' . pg_last_error());
	$query2=pg_query("UPDATE project SET raised = raised+'$amount' WHERE project_id = '$project_id'") or die('Query failed:1 ' . pg_last_error());
	if($query1&&$query2)
	{
		header('location:'.$url);
	}
}
?>
</body>
</html>