<?php

include("../../config.php");

if(!isset($_POST['username'])) {
	echo "ERROR: could not find username";
	exit();
}

if(!isset($_POST['email'])) {
	echo "ERROR: could not find email";
	exit();
}

if(isset($_POST['username']) && isset($_POST['email'])) {
	$username = $_POST['username'];
	$email = $_POST['email'];

	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo "ERROR: please enter a valid email";
		exit();
	}

	$emailCheck = mysqli_query($con, "
		 SELECT email 
		   FROM users
				WHERE email='$email'
				AND username != '$username'
	");

	if(mysqli_num_rows($emailCheck) > 0) {
		echo "Sorry, that email is already use";
		exit();
	}

	$updateQuery = mysqli_query($con, "
		 UPDATE users
		    SET email = '$email'
				WHERE username = '$username'
	");
}

echo "Email successfully updated";

?>
