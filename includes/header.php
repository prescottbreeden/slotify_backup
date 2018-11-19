<?php
include('includes/config.php');
include('includes/classes/Artist.php');
include('includes/classes/Album.php');
include('includes/classes/Song.php');
include('includes/classes/User.php');

// session_destroy(); LOGOUT

if(isset($_SESSION['userLoggedIn'])) {
	$userLoggedIn = $_SESSION['userLoggedIn'];
	$user = new User($con, $userLoggedIn);
	$lp_album = $user->getLastPlayedAlbum();
	$lp_album_order = $user->getLastPlayedAlbumOrder();
	
	echo "<script>userLoggedIn = '$userLoggedIn';</script>";
}
else {
	header("Location: register.php");
}

if(isset($_GET['term'])) {
	$term = urldecode($_GET['term']);
}
else {
	$term = '';
}


/* function getID() { */
/* 	return $_GET['id']; */
/* } */

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Slotify | Video Game Music Player</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,900" rel="stylesheet">
	<link rel="stylesheet" href="public/css/styles.css">
  <link rel="shortcut icon" href="public/images/favicon.ico" type="image/x-icon">
	<script
			  src="https://code.jquery.com/jquery-3.3.1.min.js"
			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
			  crossorigin="anonymous"></script>	
	<script src="public/js/app.js"></script>
</head>
<body>
<script>

	$('.search__input').focus();

	$(function() { 
		
		var val = $('.search__input').val();
		$('.searchInput').text(val);

		
		$(document).keypress(function(e) {
			if(e.which == 13 && e.target != document.body) {
				openPage('search.php?term=' + val);
			}
		});

		$('.search__input').keyup(function() {
			val = $('.search__input').val();
			$('.searchInput').text(val);
		});

	});

</script>
<div class="msg-box">
	<div class="msg-box__text"></div>
	<span class="msg-box__icon">&times;</span>
</div>
	<div class="content-wrapper">
		<div class="content">
			<nav class="nav">
				<div class="nav-bar">
					<span 
						role="link"
						tabindex="0"
						onclick="openPage('index.php')"
						class="nav-bar__logo"> 
						<?php include 'includes/icons/logo.html'; ?>
					</span>
					<div class="nav-bar__list">
						<div class="nav-bar__item">
							<span 
								role="link"
								tabindex="0"
								onclick="openPage('index.php')"
								class="nav-bar__item--link">
								Browse
							</span>
						</div>
						<div class="nav-bar__item">
							<span 
								role="link"
								tabindex="0"
								onclick="openPage('your_music.php')"
								class="nav-bar__item--link">
								Your Music	
							</span>
						</div>
					</div>
				</div>
			</nav>
			<section class="top-bar">
				<div class="top-bar__nav-box">
					<div class="top-bar__nav-btn">
						<div 
							onclick="goBack()"
							class="top-bar__nav-btn--btn">
							<svg class="top-bar__icon">
								<use xlink:href="public/images/icomoon/sprite.svg#icon-chevron-left"></use>
							</svg>	
						</div>
						<div 
							onclick="goForward()"
							class="top-bar__nav-btn--btn">
							<svg class="top-bar__icon">
								<use xlink:href="public/images/icomoon/sprite.svg#icon-chevron-right"></use>
							</svg>	
						</div>
					</div>
					<div class="search">
						<input 
							placeholder="Search"
							class="search__input" 
							type="text" 
							value="<?php echo $term; ?>">
						<button class="search__button">
							<svg class="search__icon">
								<use xlink:href="public/images/icomoon/sprite.svg#icon-search"></use>
							</svg>	
						</button>
					</div>
				</div>
				<div class="top-bar__empty-space"></div>
				<div class="top-bar__user-menu">
					<div title="Profile" class="top-bar__user-info">
						<img 
							class="top-bar__user-info--avatar" 
							src="public/images/profile-pics/head_emerald.png" 
							alt="user avatar">
						<p 
							onclick="openPage('profile.php')"
							class="top-bar__user-info--username"><?php echo $_SESSION['userLoggedIn']?></p>
					</div>
					<svg 
						onclick="showUserMenu()"
						id="user-menu" class="top-bar__menu">
						<use xlink:href="public/images/icomoon/sprite.svg#icon-chevron-down"></use>
					</svg>
					<div 
						onclick="openPage('profile.php')"
						class="usermenu">
						<div class="usermenu-item">
							View Profile
						</div>
						<div 
							onclick="logout()"
							class="usermenu-item">
							Logout
						</div>
					</div>
				</div>
			</section>
			<div class="dynamic-content">
