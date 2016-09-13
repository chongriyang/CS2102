<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';
$_SESSION['timeout'] = time() + 1800;
echo $_GET['id'];
echo $_GET['data'];
echo $_GET['key'];
echo "heelo";
if($_GET['id'] && $_GET['data']) {
	$id = $_GET['id'];
	$data = $_GET['data'];
	$key = $_GET['key'];
	$query_update_user = "UPDATE person SET $key = '$data' WHERE user_id = '$id'";
	$result_update_user = pg_query($query_update_user) or die('Query failed1: ' . pg_last_error());
}
	if ($result_update_user) {
		echo "success";
	} else {
		echo "failed";
	}
	include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/close_connection.php';
?>