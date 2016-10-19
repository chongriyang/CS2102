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
	if(isset($_GET['edit_transaction'])&&isset($_GET['amount']))
	{
		$transaction_id=$_GET['edit_transaction'];
		$amount=$_GET['amount'];

		$query=pg_query("UPDATE transaction SET amount='$amount' WHERE transaction_id='$transaction_id'") or die('Query failed:1 ' . pg_last_error());
		if($query)
		{
			header('location:'.$url);
			die();
		}
	}
	?>
</body>
</html>