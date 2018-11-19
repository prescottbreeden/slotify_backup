<?php

include("../../config.php");

if(!isset($_POST['oldPassword'])) {
	echo "ERROR: password not detected";
	exit();
}

if(!isset($_POST['username'])) {
	echo "ERROR: username not detected";
	exit();
}

$username = $_POST['username'];
$oldPassword = $_POST['oldPassword'];
$encryptedPassword = md5($oldPassword);

$passwordCheck = mysqli_query($con, "
		 SELECT password 
		   FROM users
				WHERE username='$username'
				AND password='$encryptedPassword' 
");

if(mysqli_num_rows($passwordCheck) != 1) {
	echo "ERROR: Password does not match current";
	exit();
}

echo "Success: enter a new password";

?>
