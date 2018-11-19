<?php
include("../../config.php");

if(isset($_POST['playlist_id']) && isset($_POST['song_id']) && isset($_POST['pl_order'])) {
	$playlist_id = $_POST['playlist_id'];
	$song_id = $_POST['song_id'];
	$pl_order = $_POST['pl_order'];

	$query = mysqli_query($con, "
		 DELETE 
		   FROM pl_songs 
				WHERE playlist_id='$playlist_id'
				AND song_id='$song_id'	
				AND playlist_order='$pl_order'
	");

	$name_query = mysqli_query($con, "
		 SELECT name
		   FROM playlists
				WHERE playlist_id='$playlist_id'
	");
	$row = mysqli_fetch_array($name_query);
	$playlist_name = $row['name'];

	echo "Song removed from playlist: '$playlist_name'";
}
else {
	echo "playlistId or SongID was not passed into ajax";
}

?>
