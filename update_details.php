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

<section class="userdetails">
	<div class="userdetails__primary-heading">
		Edit Profile 
	</div>
	<div class="userdetails__container">
		<h2 class="userdetails__secondary-heading">email</h2>
		<input 
			value="<?php echo $user->getEmail(); ?>"
			name="email"
			placeholder="Enter new email address"
			class="userdetails__input" 
			type="email">
		<?php // echo $account->getError(Constants::$error_login_failed); ?>
		<button 
			onclick="updateEmail()"
			class="userdetails__submit-btn">
			Update Email		
		</button>
	</div>
	<div class="userdetails__container">
		<div class="userdetails__container--pwd">
			<div class="userdetails__container--pwd-block">
				<h2 class="userdetails__secondary-heading">password</h2>
				<input 
					name="oldPassword"
					placeholder="Enter current password"
					class="userdetails__input" 
					type="password">
				<button 
					onclick="checkOldPassword()"
					class="userdetails__submit-btn">
					Submit
				</button>
			</div>
			<div id="new_pw" class="userdetails__container--pwd-block">
				<h2 class="userdetails__secondary-heading">new password</h2>
				<input 
					name="newPassword1"
					placeholder="Enter new password"
					class="userdetails__input" 
					type="password">
				<br>
				<input 
					name="newPassword2"
					placeholder="Confirm new password"
					class="userdetails__input" 
					type="password">
				<?php // echo $account->getError(Constants::$error_login_failed); ?>
				<button 
					onclick="updatePassword()"
					class="userdetails__submit-btn">
					Update Password
				</button>
			</div>
		</div>
	</div>

</section>
