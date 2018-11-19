<?php

include("../../config.php");

if(!isset($_POST['username'])) {
	echo "ERROR: could not find username";
	exit();
}

if(!isset($_POST['pw'])) {
	echo "ERROR: could not find password";
	exit();
}

$username = $_POST['username'];
$pw = $_POST['pw'];
$encryptedPW = md5($pw);

$emailCheck = mysqli_query($con, "
	 UPDATE users
			SET password = '$encryptedPW'
			WHERE username='$username'
");

echo "Password successfully updated";

?>
