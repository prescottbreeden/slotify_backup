<?php
include("../../config.php");
include("../../classes/User.php");

if(isset($_POST['playlistId'])) {
	$playlist_id = $_POST['playlistId'];

}
else {
	echo "um.... can't do boss - i needz an id";
}

$songsQuery = mysqli_query($con, "
		DELETE 
			FROM pl_songs 
			WHERE playlist_id ='$playlist_id'");

$playlistquery = mysqli_query($con, "
		DELETE 
			FROM playlists 
			WHERE playlist_id ='$playlist_id'");


?>


