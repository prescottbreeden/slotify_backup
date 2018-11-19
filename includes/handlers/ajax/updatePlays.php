<?php
include("../../config.php");

if(isset($_POST['songId'])) {
	$songId = $_POST['songId'];

	$query = mysqli_query($con, "
		 UPDATE songs 
			SET play_count = play_count+1 
				WHERE song_id='$songId'");
}


?>
