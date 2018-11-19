<?php
include('includes/includedFiles.php');

if(isset($_SESSION['userLoggedIn'])) {
	$userLoggedIn = $_SESSION['userLoggedIn'];
	echo "<script>userLoggedIn = '$userLoggedIn';</script>";
	$user = new User($con, $userLoggedIn);
}
else {
	header("Location: register.php");
}


?>

<section class="profile">
	<div class="profile__image">
		<img src="<?php echo $user->getProfilePic(); ?>" alt="profile image">
	</div>
	<div class="profile__fullname">
		<?php echo $user->getFullName(); ?>
	</div>
	<div 
		onclick="openPage('update_details.php')"
		class="profile__edit-btn">
		Edit profile
	</div>
	<div 
		onclick="logout()"
		class="profile__logout-btn">
		Logout
	</div>


</section>


