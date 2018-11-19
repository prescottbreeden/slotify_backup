<?php
include("../../config.php");
include("../../classes/User.php");

if(isset($_POST['pl_name']) && isset($_POST['username'])) {
	$user = new User($con, $_POST['username']);
	$user_id = $user->getId();
	$pl_name = $_POST['pl_name'];
	$query = mysqli_query($con, 
		"INSERT INTO playlists 
					(name, user_id) 
			VALUES	('$pl_name', '$user_id')");
}
else {
	echo "something broke in ajax handler";
}


?>


