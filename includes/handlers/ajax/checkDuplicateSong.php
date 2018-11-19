<?php
include("../../config.php");
 
if(isset($_POST['playlistId']) && isset($_POST['songId'])) {
    $playlistId = $_POST['playlistId'];
    $songId = $_POST['songId'];
 
	$query = mysqli_query($con, "
		 SELECT * 
		   FROM pl_songs 
				WHERE playlist_id='$playlistId' 
				AND song_id='$songId'
	");
    
    echo mysqli_num_rows($query);
}
 
?>
