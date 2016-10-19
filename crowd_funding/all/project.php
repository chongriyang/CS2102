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
}

if (!empty($_POST['login_submit'])) {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';
	$_SESSION['timeout'] = time() + 1800;
	$email = trim($_POST['email']);
	$email = strtolower($email);
	$password = strip_tags($_POST['password']);
	$remember = false;

	$salt = "F3#@$%ewgSDGaskjf#@$EFsdFGqwjfqad@#$^$%&segjlkszflijs";
	$password = hash('sha256', $salt.$password);

	$query_login = "SELECT p.email, p.password, p.user_id, p.name, p.is_admin FROM person p WHERE p.email = '$email' AND p.is_activated = '1' LIMIT 1";

	if (!empty($email) && !empty($password)) {
		if (!preg_match('/[^A-Za-z0-9\@.]/', $email)) {
			$result_login = pg_query($query_login) or die('Query failed1: ' . pg_last_error());
			if ($result_login) {
				$row = pg_fetch_row($result_login);
				$db_email = $row[0];
				$db_password = $row[1];
				$db_user_id = $row[2];
				$db_username= $row[3];
				$db_is_admin= $row[4];

				if ($email == $db_email && $password == $db_password) {
					$_SESSION['username'] = $db_username;	
					$_SESSION['user_id'] = $db_user_id;
					$_SESSION['is_admin'] = $db_is_admin;
					if ($_POST['remember_me'] == '1') {

						$salt = "askhd@!sadknsa!@$R%$*&)(*_GFJsjhfj$WETkahfliqjafloaijfi;oeajfo;k";
						$identifier = hash('sha256', $salt.$db_email);
						$key = md5(uniqid(rand(), true)); //$timeout = time() + 604800; // 7 days
						$timeout = new DateTime('+7 day');
						$timeout =$timeout->format('Y-m-d H:i:s');
						$query_existing_cookie = "SELECT p.user_id, c.timeout FROM person p, cookie c WHERE p.user_id = c.user_id AND p.email = '$db_email'";

						$result_select_cookie = pg_query($query_existing_cookie) or die('Query failed2: ' . pg_last_error());
						if (pg_num_rows($result_select_cookie)) {

							$query_update_cookie = "UPDATE cookie SET key = '$key', timeout = '$timeout' WHERE user_id = '$db_user_id'";
							$result_update_cookie = pg_query($query_update_cookie) or die('Query failed1: ' . pg_last_error());;
							
							if ($result_update_cookie) {
								$timeout = strtotime($timeout);
								setcookie('user', "$identifier:$key", $timeout, "/");
							}
						} else {

							$query_insert_cookie = "INSERT INTO cookie (user_id, identifier, key, timeout) VALUES ('$db_user_id', '$identifier', '$key', '$timeout')";
							$result_insert_cookie = pg_query($query_insert_cookie) or die('Query failed:3 ' . pg_last_error());;
							if ($result_insert_cookie) {
								$timeout = strtotime($timeout);
								setcookie('user', "$identifier:$key", $timeout, "/");
							}
						}
					}
					if ($db_is_admin === 't') {
						header('Location: /crowd_funding/member/administrator/administrator.php');
						die();
					} else {
						header('Location: /crowd_funding/member/user/user.php');
						die();
					}
				} else if ($email == $db_email && $password != $db_password) {
					echo "Your email account or password is incorrect. Please try again.";
				} else {
					echo "The account doesn't exist. If you do not have an account, please sign up.";
				}
			}
		} else {
			echo "You entered an invalid email account with special characters (e.g. '!', '$', '#'). Please omit them and try again.";
		}
	} else if (!empty($email)) {
		if (!preg_match('/[^A-Za-z0-9\@.]/', $email)) {
			echo "Please enter your password.";
		} else {
			echo "You entered an invalid email account with special characters (e.g. '!', '$', '#'.). Please omit them and try again.";
		}
	} else if (!empty($password)) {
		echo "Please enter your email account.";
	} else {
		echo "Please enter your email account and password.";
	}
	include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/close_connection.php';
}
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->  
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->  
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->  
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="unititled">
	<meta name="keywords" content="HTML5 Crowdfunding Profile Template">
	<meta name="author" content="Audain Designs">
	<link rel="shortcut icon" href="favicon.ico">  
	<title>Launch - HTML5 Crowdfunding Profile Template</title>

	<!-- Gobal CSS -->
	<!-- <link href="assets/css/bootstrap.min.css" rel="stylesheet"> -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	
	<!-- Template CSS -->
	<link href="../../css/product.css" rel="stylesheet">

	<!--Fonts-->
	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>
	<?php require_once($_SERVER['DOCUMENT_ROOT'].'/crowd_funding/header/navbar.php'); ?>
	<?php $dbconn = pg_connect("host=localhost port=5432 dbname=crowd_funding user=postgres password=password123")or die('Could not connect: ' . pg_last_error());?>
	<?php 
		$projectnumber = $_GET["project_id"];

		$query='SELECT * FROM project p WHERE p.project_id='. $projectnumber;
		$result = pg_query($query) or die('Query failed: ' . pg_last_error());
		if ($result) {
		$row = pg_fetch_row($result);
		// $project_id = $row[0];
		$project_name = $row[1];
		$project_owner =$row[2];
		$project_description = $row[3];
		$project_video = $row[4];
		$start_date= $row[5];
		$end_date= $row[6];
		$amount= $row[7];
		$raised= $row[8];
		}
		// echo $amount;
		// echo $project_name;
		echo $project_id;
		// echo $project_description;
		// echo $start_date;
		// echo $end_date;
		// echo $amount;
		// echo $raised;

		//calculate funding percentage
		$percentage = ($raised/$amount) * 100;
		
		//calculate days remaining for project and safe into $daysleft
		$current=date("Y-m-d");
		$date1=new DateTime($current);
		$date2=new DateTime($end_date);
		$daysleft= $date1->diff($date2);
	?>
	<!--header-->
	<header class="header">
		<div class="container">
			<div class="row">
				<div class="goal-summary pull-left">
					<div class="backers">
						<h3>5234</h3>
						<span>backers</span>
					</div>
					<div class="funded">
						<h3>$<?php echo number_format($raised); ?></h3>
						<span>raised out of $<?php echo number_format($amount); ?></span>
					</div>
					<div class="time-left">
						<h3><?php echo $daysleft->days;?></h3>
						<span>days left to go</span>
					</div>
					<div class="reminder last">
						<a href="#"><i class="fa fa-star"></i> REMIND ME</a>
					</div>
				</div>
			</div>
		</div>
	</header>
	<!--/header-->
	<!--main content-->
	<div class="main-content">
		<div class="container">
			<div class="row">
				<div class="content col-md-8 col-sm-12 col-xs-12">
					<div class="section-block">
						<div class="funding-meta">
							<h1><?php echo $project_name; ?></h1>
							<span class="type-meta"><i class="fa fa-user"></i> <?php echo $project_owner?></span>
							<span class="type-meta"><i class="fa fa-tag"></i> <a href="#">crowdfunding</a>, <a href="#">launch</a> </span>
							<!--img src="assets/img/image-heartbeat.jpg" class="img-responsive" alt="launch HTML5 Crowdfunding"-->
							<div class="video-frame">
								<iframe src="<?php echo $project_video?>" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
							</div>
							<p><?php echo $project_description ?></p>
							<h2>$<?php echo number_format($raised); ?></h2>							
							<span class="contribution">raised by <strong>5,234</strong> ready to launch</span>
							<div class="progress">
								<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage?>%;">
									<span class="sr-only"><?php echo $percentage?>% Complete</span>
								</div>
							</div>
							<span class="goal-progress"><strong><?php echo floor($percentage);?>%</strong> of $<?php echo number_format($amount); ?> raised</span>
						</div>
						<span class="count-down"><strong><?php echo $daysleft->days;?></strong>Days to go.</span>
						<a href="#" class="btn btn-launch">HELP LAUNCH</a>
					</div>
					<!--signup-->
					<div class="section-block signup">
						<div class="sign-up-form">
							<form>
								<p>Sign up now for updates and a chance to win a free version of launch!</p>
								<input class="signup-input" type="text" name="email" placeholder="Email Address"><button class="btn btn-signup" type="submit"><i class="fa fa-paper-plane"></i></button>
							</form>
						</div>
					</div>
					<!--/signup-->
					<!--tabs-->
					<div class="section-block">
						<div class="section-tabs">
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active"><a href="#about" aria-controls="about" role="tab" data-toggle="tab">About</a></li>
								<li role="presentation"><a href="#updates" aria-controls="updates" role="tab" data-toggle="tab">Updates</a></li>
							</ul>
						</div>
					</div>
					<!--/tabs-->
					<!--tab panes-->
					<div class="section-block">
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="about">
								<div class="about-information">
									<h1 class="section-title">ABOUT LAUNCH</h1>
									<p>Suspendisse luctus at massa sit amet bibendum. Cras commodo congue urna, vel dictum velit bibendum eget. Vestibulum quis risus euismod, facilisis lorem nec, dapibus leo. Quisque sodales eget dolor iaculis dapibus. Vivamus sit amet lacus ipsum. Nullam varius lobortis neque, et efficitur lacus. Quisque dictum tellus nec mi luctus imperdiet. Morbi vel aliquet velit, accumsan dapibus urna. Cras ligula orci, suscipit id eros non, rhoncus efficitur nisi.</p>
									<p>Quisque fermentum blandit ex at commodo. Nulla facilisi. Pellentesque porttitor nisi tellus, at gravida mi interdum et. Nulla vestibulum imperdiet libero eget mattis. Vestibulum porttitor, nibh quis sagittis tincidunt, velit orci molestie magna, in congue tortor mauris sit amet eros. Nam dictum gravida tempus.</p>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="updates">
								<div class="update-information">
								<h1 class="section-title">UPDATES</h1>
									<!--update items-->
									<div class="update-post">
										<h4 class="update-title">We've started shipping!</h4>
										<span class="update-date">Posted 2 days ago</span>
										<p>Suspendisse luctus at massa sit amet bibendum. Cras commodo congue urna, vel dictum velit bibendum eget. Vestibulum quis risus euismod, facilisis lorem nec, dapibus leo. Quisque sodales eget dolor iaculis dapibus. Vivamus sit amet lacus ipsum. Nullam varius lobortis neque, et efficitur lacus. Quisque dictum tellus nec mi luctus imperdiet. Morbi vel aliquet velit, accumsan dapibus urna. Cras ligula orci, suscipit id eros non, rhoncus efficitur nisi.</p>
									</div>
									<div class="update-post">
										<h4 class="update-title">Launch begins manufacturing </h4>
										<span class="update-date">Posted 9 days ago</span>
										<p>Suspendisse luctus at massa sit amet bibendum. Cras commodo congue urna, vel dictum velit bibendum eget. Vestibulum quis risus euismod, facilisis lorem nec, dapibus leo. Quisque sodales eget dolor iaculis dapibus. Vivamus sit amet lacus ipsum. Nullam varius lobortis neque, et efficitur lacus. Quisque dictum tellus nec mi luctus imperdiet. Morbi vel aliquet velit, accumsan dapibus urna. Cras ligula orci, suscipit id eros non, rhoncus efficitur nisi.</p>
									</div>
									<div class="update-post">
										<h4 class="update-title">Designs have now been finalized</h4>
										<span class="update-date">Posted 17 days ago</span>
										<p>Suspendisse luctus at massa sit amet bibendum. Cras commodo congue urna, vel dictum velit bibendum eget. Vestibulum quis risus euismod, facilisis lorem nec, dapibus leo. Quisque sodales eget dolor iaculis dapibus. Vivamus sit amet lacus ipsum. Nullam varius lobortis neque, et efficitur lacus. Quisque dictum tellus nec mi luctus imperdiet. Morbi vel aliquet velit, accumsan dapibus urna. Cras ligula orci, suscipit id eros non, rhoncus efficitur nisi.</p>
									</div>
									<!--/update items-->
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--/tabs-->
				<!--/main content-->
				<!--sidebar-->
				<div class="content col-md-4 col-sm-12 col-xs-12">
					<div class="section-block summary">
						<h1 class="section-title">LAUNCH</h1>
						<div class="profile-contents">
							<h2 class="position">Sky Rocketing Your Funding Campaign</h2>
							<img src="assets/img/profile-img.jpg" class="profile-image img responsive" alt="John Doe">
							<!--social links-->
							<ul class="list-inline">
								<li><a href="#"><i class="fa fa-twitter"></i></a></li>
								<li><a href="#"><i class="fa fa-facebook"></i></a></li>
								<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
								<li><a href="#"><i class="fa fa-linkedin"></i></a></li>
								<li><a href="#"><i class="fa fa-git"></i></a></li>
							</ul>
							<!--/social links-->
							<a href="#" class="btn btn-contact"><i class="fa fa-envelope"></i>CONTACT US</a>
						</div>
					</div>
					<div class="section-block">
						<h1 class="section-title">REWARDS</h1>
						<!--reward blocks-->
						<div class="reward-block">
							<h3>$10</h3>
							<h2>Early Bird</h2>
							<p>Curabitur accumsan sem sed velit ultrices fermentum. Pellentesque rutrum mi nec ipsum elementum aliquet. Sed id vestibulum eros. Nullam nunc velit, viverra sed consequat ac, pulvinar in metus.</p>
							<span><i class="fa fa-users"></i> 180 backers</span>
							<a href="" class="btn btn-reward">GET THIS REWARD</a>
						</div>
						<div class="reward-block popular">
							<h3>$20</h3>
							<h2>Value Bird</h2>
							<p>Curabitur accumsan sem sed velit ultrices fermentum. Pellentesque rutrum mi nec ipsum elementum aliquet. Sed id vestibulum eros. Nullam nunc velit, viverra sed consequat ac, pulvinar in metus.</p>
							<span><i class="fa fa-users"></i> 320 backers</span>
							<a href="" class="btn btn-reward">GET THIS REWARD</a>
						</div>
						<div class="reward-block">
							<h3>$30</h3>
							<h2>Super Bird</h2>
							<p>Curabitur accumsan sem sed velit ultrices fermentum. Pellentesque rutrum mi nec ipsum elementum aliquet. Sed id vestibulum eros. Nullam nunc velit, viverra sed consequat ac, pulvinar in metus.</p>
							<span><i class="fa fa-users"></i> 105 backers</span>
							<a href="" class="btn btn-reward">GET THIS REWARD</a>
						</div>
						<div class="reward-block last">
							<h3>$50</h3>
							<h2>Premium Bird</h2>
							<p>Curabitur accumsan sem sed velit ultrices fermentum. Pellentesque rutrum mi nec ipsum elementum aliquet. Sed id vestibulum eros. Nullam nunc velit, viverra sed consequat ac, pulvinar in metus.</p>
							<span><i class="fa fa-users"></i> 64 backers</span>
							<a href="" class="btn btn-reward">GET THIS REWARD</a>
						</div>
						<!--/reward blocks-->
					</div>
					<!--credits-->
					<div class="section-block">
						<h1 class="section-title">CREDITS</h1>
						<!--credits block-->
						<div class="credit-block sources">
							<ul class="list-unstyled">
								<li><a href="http://getbootstrap.com/"><i class="fa fa-external-link"></i>Bootstrap</a></li>
								<li><a href="http://fortawesome.github.io/Font-Awesome/"><i class="fa fa-external-link"></i>FontAwesome</a></li>
								<li><a href="https://www.google.com/fonts"><i class="fa fa-external-link"></i>Google Fonts</a></li>
								<li><a href="http://jquery.com/"><i class="fa fa-external-link"></i>jQuery</a></li>
								<li><a href="https://vimeo.com/67938315"><i class="fa fa-external-link"></i>Vimeo Video</a></li>
								<li><a href="http://uifaces.com/"><i class="fa fa-external-link"></i>Glasses Image</a></li>
							</ul>
						</div>
						<div class="credit-block license">
							<p>The Launch template was created by <a class="lined" href="http://themes.audaindesigns.com">Audain Designs</a> for use by anyone for <strong>FREE</strong> and is covered uner the <a class="lined" href="http://creativecommons.org/licenses/by/3.0/">Creative Commons Attribution 3.0 License</a>.</p>
							<p>As time goes on the template may receive updates, follow us on twitter to get notified when an update is released.</p>
							<a href="http://twitter.com/audaindesigns" class="btn btn-follow"><i class="fa fa-twitter"></i>FOLLOW US</a>
							<a href="#" class="btn btn-download"><i class="fa fa-download"></i>DOWNLOAD TEMPLATE</a>
						</div>
						<!--/credits block-->
					</div>
					<!--/credits-->
				</div>
				<!--/sidebar-->
			</div>
		</div>
	</div>
	<footer class="footer">
	<div class="container">
			<div class="row">
				<!--This template has been created under the Creative Commons Attribution 3.0 License. Please keep the attribution link below when using this template in your own project, thank you.-->
				<span class="copyright">Created by <a href="http://themes.audaindesigns.com" target="_blank">Audain Designs</a> for free use</span>
			</div>
		</div>
	</footer>
	
	<!-- Global jQuery -->
	<script type="text/javascript" src="assets/js/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	
	<!-- Template JS -->
	<script type="text/javascript" src="assets/js/main.js"></script>

	<?php 
		pg_free_result($result);
		pg_close($dbconn);

	?>
	<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/header/footer.php'); ?>
</body>
</html>
