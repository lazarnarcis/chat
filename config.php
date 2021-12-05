<?php
	$whitelist = array(
		'127.0.0.1',
		'::1'
	);
	if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
		$username = getenv('USERNAME');
		$password = getenv('PASSWORD');
		$database = getenv('DATABASE');
		$server = getenv('SERVER');
	} else {
		$username = "root";
		$password = "";
		$database = "freelancing-projects";
		$server = "localhost";
	}
	$link = mysqli_connect($server, $username, $password, $database);
	if ($link === false) {
	    die("ERROR! Could not connect to database!");
	}
?>