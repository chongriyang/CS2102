
	<?php 
	date_default_timezone_set('Asia/Singapore');
	$now = new DateTime();
	$now =$now->format('Y-m-d H:i:s');
	
	echo "TIME:". $now;
	?>
