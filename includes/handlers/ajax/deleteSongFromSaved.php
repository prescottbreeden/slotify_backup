<?php
include("../../config.php");
include("../../classes/User.php");

if(isset($_POST['song']) && isset($_POST['username'])) {
	$user = new User($con, $_POST['username']);
	$user_id = $user->getId();
	$song_id = $_POST['song'];
	$query = mysqli_query($con, "
		DELETE
				FROM saved_songs
				WHERE song_id ='$song_id'
				AND user_id = '$user_id'
	");

	echo "Song removed from your music";
}
else {
	echo "something broke in ajax handler";
}

?>

