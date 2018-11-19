<?php

if (ob_get_level()) {
}
else {
	ob_start();

}

if (session_status() == PHP_SESSION_NONE) {
	session_start();
	$timezone = date_default_timezone_set('America/Los_Angeles');

	define("DB_HOST", "localhost");
	define("DB_USER", "trashpanda");
	define("DB_PASSWORD", "rubberbabybuggybumpers");
	define("DB_DATABASE", "slotify");
}


/* echo var_dump(function_exists('mysqli_connect')); */

$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

if(mysqli_connect_errno()) {
	echo "Failed to connect: " . mysqli_connect_errno();
}

?>
