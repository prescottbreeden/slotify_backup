<?php

include('includes/config.php');
include('includes/classes/Constants.php');
include('includes/classes/Account.php');

$account = new Account($con);

include('includes/handlers/register-handler.php');
include('includes/handlers/login-handler.php');

function getInputValue($name) {
	if(isset($_POST[$name])) {
		echo $_POST[$name];
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Welcome to Slotify!</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,900" rel="stylesheet">
	<link rel="stylesheet" href="public/css/styles.css">
	<script
			  src="https://code.jquery.com/jquery-3.3.1.min.js"
			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
			  crossorigin="anonymous"></script>	
	<script src="public/js/app.js"></script>
</head>
<body>

<?php

if(isset($_POST['registerButton'])) {
	echo '<script>
			$(document).ready(function() {
				$("#loginForm").hide();
				$("#registerForm").show();
			});
		</script>';
}
else {
	echo '<script>
			$(document).ready(function() {
				$("#loginForm").show();
				$("#registerForm").hide();
			});
		</script>';
}

?>
	<section class="register" id="background">
    <form action="register.php" method="POST">
        <button 
          id="guest_login"
          class="register__login--btn-guest"
          name=guest-loginButton>
            Login As Guest 
        </button>
    </form>
		<div class="register__container" id="register_container">
			<div class="register__login" id="input_container">

				<form 
					class="register__login--form" 
					id="loginForm" 
					action="register.php" method="POST">

					<h2 class="register__login--heading">Login to your account</h2>
					<p class="register__login--input">
						<label for="loginUsername">Username</label>
						<input 
							id="loginUsername" 
							name="loginUsername" 
							placeholder="Enter your username"
							required
							value="<?php getInputValue('loginUsername') ?>"
							type="text">
					</p>
					<p class="register__login--input">
						<label for="loginPassword">Password</label>
						<input 
							id="loginPassword" 
							name="loginPassword" 
							placeholder="Enter your password"
							required
							type="password">
						<?php echo $account->getError(Constants::$error_login_failed); ?>
					</p>
					<button 
						id="login_submit"
						class="register__login--btn" 
						type="submit" 
						name=loginButton>
							Login
					</button>

					<div class="register__login--has-account-text">
						<span id="hideLogin">Don't have an account yet? Signup here.</span>
					</div>
				</form>

				<form 
					class="register__login--form"
					id="registerForm" 
					action="register.php" method="POST">

					<h2 class="register__login--heading">Create your free account</h2>
					<p class="register__login--input">
						<label for="username">Username</label>
						<input 
							id="username" 
							name="username" 
							placeholder="Your username"
							value="<?php getInputValue('username') ?>"
							required
							type="text">
						<?php echo $account->getError(Constants::$error_un_len); ?>
						<?php echo $account->getError(Constants::$error_un_taken); ?>
					</p>
					<p class="register__login--input">
						<label for="firstName">First Name</label>
						<input 
							id="firstName" 
							name="firstName" 
							placeholder="Your first name"
							value="<?php getInputValue('firstName') ?>"
							required
							type="text">
						<?php echo $account->getError(Constants::$error_fn_len); ?>
					</p>
					<p class="register__login--input">
						<label for="lastName">Last Name</label>
						<input 
							id="lastName" 
							name="lastName" 
							placeholder="Your last name"
							value="<?php getInputValue('lastName') ?>"
							required
							type="text">
						<?php echo $account->getError(Constants::$error_ln_len); ?>
					</p>
					<p class="register__login--input">
						<label for="email">Email</label>
						<input 
							id="email" 
							name="email" 
							placeholder="Your Email"
							value="<?php getInputValue('email') ?>"
							required
							type="email">
					</p>
					<p class="register__login--input">
						<label for="email2">Confirm Email</label>
						<input 
							id="email2" 
							name="email2" 
							placeholder="Re-enter your email"
							value="<?php getInputValue('email2') ?>"
							required
							type="email">
						<?php echo $account->getError(Constants::$error_em_match); ?>
						<?php echo $account->getError(Constants::$error_em_valid); ?>
						<?php echo $account->getError(Constants::$error_em_taken); ?>
					</p>
					<p class="register__login--input">
						<label for="password">Password</label>
						<input 
							id="password" 
							name="password" 
							required
							placeholder="Your password"
							type="password">
					</p>
					<p class="register__login--input">
						<label for="password2">Confirm Password</label>
						<input 
							id="password2" 
							name="password2" 
							required
							placeholder="Re-enter your password"
							type="password">
						<?php echo $account->getError(Constants::$error_pw_len); ?>
						<?php echo $account->getError(Constants::$error_pw_match); ?>
						<?php echo $account->getError(Constants::$error_pw_valid); ?>
					</p>
					<button 
						id="register_submit"
						class="register__login--btn"
						type="submit" 
						name=registerButton>
							Sign Up
					</button>
					<div class="register__login--has-account-text">
						<span id="hideRegister">Already have an account? Login here.</span>
					</div>
				</form>
			</div>
			<div class="register__promo-text">
				<h2 class="register__promo-text--heading">Video Game Music<br> On Demand</h2>
				<h3 class="register__promo-text--sub-heading">Your favorite video game music free</h3>
				<ul class="register__promo-text--list">
					<li class="register__promo-text--item">
						<svg class="register__promo-text--icon">
							<use xlink:href="public/images/icomoon/sprite.svg#icon-checkmark"></use>
						</svg>
				    Get your nerd on
					</li>
					<li class="register__promo-text--item">
						<svg class="register__promo-text--icon">
							<use xlink:href="public/images/icomoon/sprite.svg#icon-checkmark"></use>
						</svg>
						Create your own playtlists
					</li>
					<li class="register__promo-text--item easter-egg">
						<svg class="register__promo-text--icon">
							<use xlink:href="public/images/icomoon/sprite.svg#icon-checkmark"></use>
						</svg>
						<span 
							onclick="changeBackground()"
							class="easter-egg--text">
              Easter egg text here
						</span>
					</li>
				</ul>
			</div>
		</div>
	</section>
<section id="no_mobile" class="no-mobile">
	<div class="no-mobile__message">
		<h2>Sorry, Slotify doesn't support mobile devices.</h2>
	</div>
</section>
</body>
</html>
