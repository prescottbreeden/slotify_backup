<?php
include("../../config.php");
include("../../classes/User.php");

if(isset($_POST['albumId']) && isset($_POST['username'])) {
	$user = new User($con, $_POST['username']);
	$user_id = $user->getId();
	$album_id = $_POST['albumId'];
	$query = mysqli_query($con, 
		"INSERT INTO saved_albums 
					(album_id, user_id) 
			VALUES	('$album_id', '$user_id')");

	echo "Album saved to your music";
}
else {
	echo "something broke in ajax handler";
}


?>

