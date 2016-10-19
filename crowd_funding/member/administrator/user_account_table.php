<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
?>
<body>
<div class="row">
	<div class="col-lg-12 text-center">
		<h1>User Accounts</h1>
	</div>
</div>
	<?php require_once($_SERVER['DOCUMENT_ROOT'].'/crowd_funding/header/navbar.php'); ?>
	<!-- Search Section -->
	<section id="search_bar">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<h3>Search bar</h3>
				</div>
			</div>
		</div>

		<!-- Search form template -->

		<div class="row">
		<div class="col-lg-12 text-center">
			<form class="form-inline" method="post">
				<div class="form-group">
					<input type="text" class="form-control" name="ID" placeholder=" ID">
				</div>
				<div class="form-group">
					<input type="text" class="form-control" name="Name" placeholder="Name">
				</div>
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Email" name="Email">
				</div>
				<div class="input-group">
					<div class="input-group-addon">
						<i class="fa fa-calendar">
						</i>
					</div>
					<input class="form-control" id="date" name="Birthday" placeholder="Birthday" type="text"/>
				</div>
				<div class="input-group">
					<div class="input-group-addon">
						<i class="fa fa-calendar">
						</i>
					</div>
					<input class="form-control" id="date" name="Join_date" placeholder="Join Date" type="text"/>
				</div>
				<div class="form-group">
					<!-- <input type="text" class="form-control" name="type" placeholder="category"> -->
					<select class="select form-control" name="Gender" placeholder="Gender">
						<option value="">-</option>
						<option value="male">male</option>
						<option value="female">female</option>
					</select>
				</div>
				<div class="form-group">
					<!-- <input type="text" class="form-control" name="type" placeholder="category"> -->
					<select class="select form-control" name="Priviledge" placeholder="Priviledge">
						<option value="">-</option>
						<option value="1">admin</option>
						<option value="0">user</option>
					</select>
				</div>
				<div class="form-group">
					<!-- <input type="text" class="form-control" name="type" placeholder="category"> -->
					<select class="select form-control" name="Status" placeholder="Status">
						<option value="">-</option>
						<option value="1">activated</option>
						<option value="0">deactivated</option>
					</select>
				</div>
				<button type="submit" name="formSubmit" value="Search" class="btn btn-primary">Search</button>
			</form>
		</div>
		</div>
	</div>

</section>
</body>

<table class="table table-bordered table-striped">
	<link href="select2-bootstrap.css" rel="stylesheet" type="text/css"></link>
	<thead>
		<tr>
			<th class="">ID</th>
			<th class="">Name</th>
			<th class="">Password</th>
			<th class="">Email</th>
			<th class="">Birthday</th>
			<th class="">Join date</th>
			<th class="">Gender</th>
			<th class="">Priviledge</th>
			<th class="">Status</th>
			<th class="">Edit</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$url=$_SERVER['REQUEST_URI'];
		$_SESSION['url'] = $url;
/**********************************************************
 *check if form is submitted, if yes, start SQL query
 **********************************************************/
include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';
if(isset($_POST['formSubmit'])) 
{
	$fields = array("p.user_id", "p.name", "p.email", "p.birthday", "p.join_date", "p.gender", "p.is_admin", "p.is_activated");
	$_SESSION['timeout'] = time() + 1800;
	$conditions = array();
	$search_ID = trim($_POST['ID']);
	if ($search_ID != '') {
		$conditions[] = $fields[0] . ' = ' . strtolower($search_ID);
	}
	$search_name = trim($_POST['Name']);
	if ($search_name != '') {
		$conditions[] = $fields[1] . ' LIKE \'%' . strtolower($search_name) . '%\'';
	}
	$search_email = trim($_POST['Email']);
	if ($search_email != '') {
		$conditions[] = $fields[2] . ' LIKE \'%' . strtolower($search_email) . '%\'';
	}
	$search_birthday = trim($_POST['Birthday']);
	if ($search_birthday != '') {
		$conditions[] = $fields[3] . ' = \'' . strtolower($search_birthday) . '\'';
	}
	$search_join_date = trim($_POST['Join_date']);
	if ($search_join_date != '') {
		$conditions[] = $fields[4] . ' = \'' . strtolower($search_join_date) . '\'';
	}
	$search_gender = trim($_POST['Gender']);
	if ($search_gender != '') {
		$conditions[] = $fields[5] . ' = \'' . strtolower($search_gender) . '\'';
	}
	$search_priviledge = trim($_POST['Priviledge']);
	if ($search_priviledge != '') {
		$conditions[] = $fields[6] . ' = \'' . strtolower($search_priviledge) . '\'';
	}
	$search_status = trim($_POST['Status']);
	if ($search_status != '') {
		$conditions[] = $fields[7] . ' = \'' . strtolower($search_status) . '\'';
	}
}
	$query = "SELECT p.user_id as user_id, p.name as user_name, p.email as user_email, p.birthday as user_birthday, p.join_date as user_join_date,p.gender as user_gender, 
	p.is_admin as user_priviledge, p.is_activated as user_status FROM person p ";
	/*********************************
	 *get all search keywords, if any
	 *********************************/

	/**************************************************
	 *append search query if search keywords are found 
	 **************************************************/
	if(count($conditions) > 0) {
		$query .= " WHERE " . implode (' AND ', $conditions); // you can change to 'OR', but I suggest to apply the filters cumulative
		$query .= " ORDER BY p.email ASC ";
	}
	/**************************************************
	 *display SQL query (for testing) 
	 **************************************************/
	echo '<div class="container">'; 
	echo '<div class="row">'; 
	echo '<div class="col-lg-12 text-center">';
	echo "<b>SQL: </b>".$query."<br><br>";
	echo "</div>"; 
	echo "</div>"; 
	echo "</div>"; 

	/**************************************************
	 *SQL query validate
	 **************************************************/
	$result = pg_query($query) or die('Please enter at least one search term');


	/**************************************************
	 *get SQL query result count
	 **************************************************/
	$num_rows = pg_num_rows($result);
	echo '<div class="container">';
	echo '<div class="row">';
	echo '<div class="col-lg-12 text-center">';
	echo "<b>Number of users found: </b>" . $num_rows . "<br><br>";
	echo "</div>";
	echo "</div>";
	echo "</div>";

	/**************************************************
	 *display SQL query result (html formatting)
	 **************************************************/
	echo '<div class="container">'; 
	echo '<div class="row">';
	$password_wildcard = '********';

	while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		$user[$row['user_id']] =  $row;

		if ($row['user_priviledge'] == 't') {
			$db_is_admin = "admin";
		}	 else {
			$db_is_admin = "user";
		}

		if ($row['user_status'] == 't') {
			$db_is_activated = "activated";
		}	 else {
			$db_is_activated = "de-activated";
		}


		echo "<tr><td>".$row['user_id']."</td>";
		echo "<td>".$row['user_name']."</td>";
		echo "<td>".$password_wildcard."</td>";
		echo "<td>".$row['user_email']."</td>";
		echo "<td>".$row['user_birthday']."</td>";
		echo "<td>".$row['user_join_date']."</td>";
		echo "<td>".$row['user_gender']."</td>";
		echo "<td>".$db_is_admin."</td>";
		echo "<td>".$db_is_activated."</td>";
		?>
		<td width=70><button  type="button"  class="btn btn-success"  data-toggle="modal" data-target="#<?php echo''.$row['user_id'].'';?>">Edit</button></td></tr>
		<div class="modal fade" id="<?php echo''.$row['user_id'].'';?>">
			<div class="modal-dialog">
				<div class="modal-content">

					<!-- header -->
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h3 class="modal-title"></h3>
					</div>

					<!-- body (form) -->
					<div class="modal-body">
						<form method="get" action="/crowd_funding/member/administrator/edit_user_account.php" class="form-signin">
							<h2 class="form-signin-heading">Edit Account ID: <?php echo $row['user_id'];?></h2>
							<label for="input_name" class="control-label">Name</label>
							<input type="name" id="input_name" class="form-control" placeholder="Name" name="name" value=<?php echo $row['user_name'];?> required required autofocus>
							<label for="input_email" class="control-label">Email address</label>
							<input type="email" id="input_email" class="form-control" placeholder="Email address" name="email" value=<?php echo $row['user_email'];?> required>
							<label for="input_password" class="control-label">Password</label>
							<input type="password" id="input_password" class="form-control" placeholder="Password" name="password">

							<label class="control-label requiredField" for="date">Birthday</label>
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-calendar">
									</i>
								</div>
								<input class="form-control" id="date" name="date" placeholder="YYYY-MM-DD" value=<?php echo $row['user_birthday'];?> type="text"/ required>
							</div>

							<label class="control-label" for="select1">Gender</label>
							<select class="select form-control" id="select1" name="gender">
								<option value=<?php echo $row['user_gender'];?>><?php echo $row['user_gender'];?></option>
								<option value="male">male</option>
								<option value="female">female</option>
							</select>

							<label class="control-label" for="select1">Priviledge</label>	
							<select class="select form-control" id="select2" name="priviledge">
								<option value=<?php echo $row['user_priviledge'];?>><?php echo $db_is_admin;?></option>
								<option value="TRUE">admin</option>
								<option value="FALSE">user</option>
							</select>

							<label class="control-label" for="select1">Status</label>
							<select class="select form-control" id="select3" name="status">
								<option value=<?php echo $row['user_status']; ?>><?php echo $db_is_activated;?></option>
								<option value="TRUE">activate</option>
								<option value="FALSE">deactivate</option>
							</select>
							<br><br>


							<button class="btn btn-lg btn-primary btn-block" type="submit" name="edit_user_account" value="<?php echo''.$row['user_id'].'';?>">Edit</button>
						</form>
					</div>

				</div>
			</div>
		</div>
		<?php }
	include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/close_connection.php';
	?>
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
	<script>
		$(document).ready(function(){
  var date_input=$('input[name="Birthday"]'); //our date input has the name "date"
  var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
  date_input.datepicker({
  	format: 'yyyy-mm-dd',
  	container: container,
  	todayHighlight: true,
  	autoclose: true,
  })
})
	</script>
	<script>
		$(document).ready(function(){
  var date_input=$('input[name="Join_date"]'); //our date input has the name "date"
  var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
  date_input.datepicker({
  	format: 'yyyy-mm-dd',
  	container: container,
  	todayHighlight: true,
  	autoclose: true,
  })
})
	</script>
