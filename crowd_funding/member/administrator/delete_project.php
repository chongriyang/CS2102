<html>
<body>
	<?php
	error_reporting(E_ALL & ~E_NOTICE);
	session_start();
	if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {

	} else {
		header('Location: /crowd_funding/index.php');
		die();
	}

	include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';	

	if(isset($_GET['id']))
	{
		$project_id=$_GET['id'];
		$query2=pg_query("DELETE FROM transaction t WHERE t.project_id='$project_id'")or die('Query failed: ' . pg_last_error());
		$query3=pg_query("DELETE FROM bookmark b WHERE b.project_id='$project_id'")or die('Query failed: ' . pg_last_error());

		$query1=pg_query("DELETE FROM project p WHERE p.project_id='$project_id'")or die('Query failed: ' . pg_last_error());

		if($query1&&$query2&&$query3)
		{
			header('location:/crowd_funding/member/administrator/manage_project.php');
		}
	}
	?>
</body>
</html>