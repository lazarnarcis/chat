<?php
	$username = getenv('USERNAME');
	$password = getenv('PASSWORD');
	$database = getenv('DATABASE');
	$server = getenv('SERVER');
	$link = mysqli_connect($server, $username, $password, $database);
	if ($link === false) {
	    die("ERROR! Could not connect to database!");
	}
?>