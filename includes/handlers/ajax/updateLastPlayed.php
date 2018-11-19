<?php
include("../../config.php");
include("../../classes/User.php");

if(isset($_POST['lp_album']) && isset($_POST['lp_album_order']) && isset($_POST['username'])) {
	$user = new User($con, $_POST['username']);
	$user_id = $user->getId();
	$lp_album = $_POST['lp_album'];
	$lp_album_order = $_POST['lp_album_order'];

	$query = mysqli_query($con, 
		"UPDATE users
			SET lp_album='$lp_album',
				lp_album_order='$lp_album_order' 
				WHERE user_id='$user_id'
");

	echo "last played updated";
}
else {
	echo "something broke in ajax handler";
}


?>

