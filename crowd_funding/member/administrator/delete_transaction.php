<html>
<body>
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

	if(isset($_GET['id']))
	{
		$transaction_id=$_GET['id'];
		$results = pg_query("SELECT amount,project_id FROM transaction where transaction_id='$transaction_id'");
		$query1=pg_fetch_assoc($results);
		$amount = $query1['amount'];
		$project_id = $query1['project_id'];

		$query2=pg_query("DELETE FROM transaction t where t.transaction_id='$transaction_id'")or die('Query failed: ' . pg_last_error());
		$query3=pg_query("UPDATE project SET raised = raised-'$amount' WHERE project_id = '$project_id'") or die('Query failed:1 ' . pg_last_error());


		if($query1&&$query2&&$query3)
		{
			header('location:/crowd_funding/member/administrator/manage_transaction.php');
			die();
		}
	}
	?>
</body>
</html>