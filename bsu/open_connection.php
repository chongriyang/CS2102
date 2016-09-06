	<?php
	$dbconn = pg_connect("host=localhost port=5432 dbname=bsu user=postgres password=password123")
	or die('Could not connect: ' . pg_last_error());
	?>