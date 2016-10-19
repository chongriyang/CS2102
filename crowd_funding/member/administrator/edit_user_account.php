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
	if(isset($_GET['edit_user_account'])&&isset($_GET['name'])&&isset($_GET['email'])&&isset($_GET['date'])&&isset($_GET['gender'])&&isset($_GET['priviledge'])&&isset($_GET['status']))
	{
		$edit_user_id=$_GET['edit_user_account'];
		$edit_username=$_GET['name'];
		$edit_email=$_GET['email'];
		$edit_password=$_GET['password'];
		$salt = "F3#@$%ewgSDGaskjf#@$EFsdFGqwjfqad@#$^$%&segjlkszflijs";
		$hashed_password = hash('sha256', $salt.$edit_password);
		$edit_birthday=$_GET['date'];
		$edit_gender=$_GET['gender'];
		$edit_is_admin=$_GET['priviledge'];
		$edit_is_activated=$_GET['status'];
		if ($edit_password == "") {
			$query=pg_query("UPDATE person SET name='$edit_username', email='$edit_email', birthday='$edit_birthday', gender='$edit_gender', is_admin='$edit_is_admin', 
				is_activated='$edit_is_activated' WHERE user_id='$edit_user_id'") or die('Query failed:1 ' . pg_last_error());
		} else {
			$query=pg_query("UPDATE person SET name='$edit_username', email='$edit_email', password='$hashed_password', birthday='$edit_birthday', gender='$edit_gender', is_admin='$edit_is_admin', 
				is_activated='$edit_is_activated' WHERE user_id='$edit_user_id'") or die('Query failed:1 ' . pg_last_error());
		}
		if($query)
		{
			header('location:'.$url);
			die();
		}
	}
	?>
</body>
</html>