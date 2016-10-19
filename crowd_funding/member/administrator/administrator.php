<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (time() > $_SESSION['timeout']) {
	$_SESSION['username'] = null;
	$_SESSION['user_id'] = null;
	$_SESSION['is_admin'] = null;
	$_SESSION['timeout'] = time()+1800;

	$username = null;
	$user_id = null;
	$is_admin = null;
	header('Location: /crowd_funding/index.php');
	die();
}

if (isset($_SESSION['username']) && isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && !empty(isset($_SESSION['username'])) && !empty(isset($_SESSION['user_id'])) && !empty(isset($_SESSION['is_admin']))) {
	$_SESSION['timeout'] = time() + 1800;
	$username = $_SESSION['username'];
	$user_id = $_SESSION['user_id'];
	$is_admin = $_SESSION['is_admin'];
} else {
	header('Location: /crowd_funding/index.php');
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
	<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
	<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
</head>
<body>

	<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/header/navbar.php'); ?>
	
	<div style="test-align: center">
		<a href="/crowd_funding/member/administrator/manage_user_account.php">
		    Manage User Accounts
		</a>
	</div>
	<div>
		<a href="/crowd_funding/member/administrator/manage_transaction.php">
			Manage Transactions
		</a>
	</div>
	<div>
		<a href="/crowd_funding/member/administrator/manage_project.php">
		    Manage Projects
		</a>
	</div>
	<div>
		<a href="/crowd_funding/member/administrator/create_new_user_account.php">
		    Create New User Account
		</a>
	</div>


</body>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/header/footer.php'); ?>
	</html>