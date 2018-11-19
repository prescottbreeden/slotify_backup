<?php
include("../../config.php");

if(isset($_POST['albumId'])) {
	$albumId = $_POST['albumId'];

	$query = mysqli_query($con, "
			SELECT art.name
			FROM albums AS al 
				JOIN album_artists AS aa
					ON al.album_id=aa.album_id
				JOIN artists AS art
					ON art.artist_id=aa.artist_id
				WHERE al.album_id = '$albumId'
	");

	$resultArray = mysqli_fetch_array($query);

	echo json_encode($resultArray);
}


?>
