<?php
include("../../config.php");
include("../../classes/User.php");

if(isset($_POST['song']) && isset($_POST['username'])) {
	$user = new User($con, $_POST['username']);
	$user_id = $user->getId();
	$song_id = $_POST['song'];
	$query = mysqli_query($con, 
		"INSERT INTO saved_songs 
					(song_id, user_id) 
			VALUES	('$song_id', '$user_id')");

	echo "Song saved to your music";
}
else {
	echo "something broke in ajax handler";
}


?>

