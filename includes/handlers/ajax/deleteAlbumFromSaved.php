<?php
include("../../config.php");
include("../../classes/User.php");

if(isset($_POST['albumId']) && isset($_POST['username'])) {
	$user = new User($con, $_POST['username']);
	$user_id = $user->getId();
	$album_id = $_POST['albumId'];
	$query = mysqli_query($con, "
		DELETE
				FROM saved_albums
				WHERE album_id ='$album_id'
				AND user_id = '$user_id'
	");

	echo "Album removed from your music";
}
else {
	echo "something broke in ajax handler";
}


?>

