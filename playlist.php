<?php
include('includes/includedFiles.php');

if(isset($_SESSION['userLoggedIn'])) {
	$userLoggedIn = $_SESSION['userLoggedIn'];
	echo "<script>userLoggedIn = '$userLoggedIn';</script>";
}
else {
	header("Location: register.php");
}

if(isset($_GET['id'])) {
	$playlist_id = $_GET['id'];
}
else {
	header("Location: browse.php");
}

$playlist = new Playlist($con, $playlist_id);
$owner = new User($con, $playlist->getOwnerName());
?>

<div class="warning">
	<div class="warning__text"></div>
	<div class="warning__btns">
		<div 
			onclick="deletePlaylist(<?php echo $playlist_id; ?>)" 
			id="warning_confirm" 
			class="warning__btns--confirm">
				Confirm
		</div>
		<div 
			onclick="deleteCancel()"
			id="warning_cancel" 
			class="warning__btns--cancel">
				Cancel
		</div>
	</div>
</div>


<section class='playlist'>
	<div class='playlist__header'>
		<div class='playlist__header--artwork'>
			<svg class='playlist__icon'>
				<use href='public/images/icomoon/sprite.svg#icon-videogame_asset'</use>
			</svg>	
			<div class="playlist__header--artwork-hover">
				<svg 
					onclick="playAlbum()";
					class='playlist__icon-hover'>
					<use href='public/images/icomoon/sprite.svg#icon-play2'</use>
				</svg>	
			</div>
		</div>
		<div class='playlist__header--details'>
			<div class='playlist__header--details-miniheader'>
				Playlist
			</div>
			<div class='playlist__header--details-title'>
				<?php echo $playlist->getName(); ?>
			</div>
			<div class='playlist__header--misc'>
				<span class="playlist__header--details-artist-by">Created by</span>
				<span 
					class="playlist__header--details-artist-name">
					<?php echo $playlist->getOwnerName(); ?> 
				</span>
				&bull; <?php echo $playlist->getNumberOfSongs(); ?> songs,
				<?php echo $playlist->getTotalLength(); ?> min
			</div>
			<div class='playlist__btn'>
				<div 
					onclick="playAlbum()";
					class='playlist__btn--play'>
					<p>Play</p>
				</div>
				<div 
					onclick="deleteWarning()"
					class='playlist__btn--delete'>
					<p id="pl_delete">delete playlist</p>
				</div>
			</div>
		</div>
	</div>
	<div class='tracks'>
		<div class='tracks__list'>
			<div class="tracks__list--item">
				<div class="tracks__list--number-header">#</div>
				<div class="tracks__list--name-header">Title</div>
				<div class="tracks__list--artist-header">artist</div>
				<div class="tracks__list--album-header">album</div>
				<div class='tracks__list--more-header'>
						
				</div>
				<div class="tracks__list--duration">
					<svg 
						class="tracks__list--duration-header"
						aria-label="[title]"
						<title>Duration</title>
						<use href="public/images/icomoon/sprite.svg#icon-clock"></use>
					</svg>
				</div>

			</div>

			<?php 
			$pl_songs = $playlist->getSongIds();
			$playlist_order = $playlist->getPlaylistOrder();
			$i = 1;
			foreach($pl_songs as $song) {
				$song = new Song($con, $song);
				$songArtist = $song->getArtistObject();

				echo "
					<div class='tracks__list--item'>
						<input type='hidden' class='track_listing' value='" . $song->getId() . "'>
						<div class='tracks__list--number'>
							<span>	
								$i
							</span>
							<svg 
								aria-label='[title]'
								onclick='setTrack(\"" . $song->getId() . "\", tempPlaylist, true)'
								class='tracks__list--number-play'>
								<title>Play</title>
								<use href='public/images/icomoon/sprite.svg#icon-play2'></use>
							</svg>
							<svg 
								class='tracks__list--number-sound'>
								<use href='public/images/icomoon/sprite.svg#icon-volume-2'</use>
							</svg>
							<svg 
								onclick='pauseSong()'
								class='tracks__list--number-pause'>
								<use href='public/images/icomoon/sprite.svg#icon-pause2'</use>
							</svg>
						</div>
						<div class='tracks__list--name'>" . $song->getTitle() . "</div>
						<div 
							onclick='openPage(\"artist.php?id=" . $song->getArtistId() . "\")'
							class='tracks__list--artist'>" . $songArtist->getName() . "
						</div>
						<div 
							onclick='openPage(\"album.php?id=" . $song->getAlbumId() . "\")'
							class='tracks__list--artist'>" . $song->getAlbumName() . "</div>
						<div class='tracks__list--more'>
							<input type='hidden' class='songId' value='" . $song->getId() . "'>
							<input type='hidden' class='albumId' value='" . $song->getAlbumId() . "'>
							<input type='hidden' class='artistId' value='" . $song->getArtistId() . "'>
							<input type='hidden' class='playlistOrder' value='" . $playlist_order[$i-1] . "'>
							<svg 
								class='options__button'
								onclick='showOptionsMenu(this)' 
								aria-label='[title]'
								<title>More</title>
								<use href='public/images/icomoon/sprite.svg#icon-more-horizontal'></use>
							</svg>
						</div>
						<div class='tracks__list--duration'>" . $song->getDuration() . "</div>
					</div>

					";

				$i = $i+1;
			}

			?>

			<script>
				var tempSongIds = '<?php echo json_encode($pl_songs); ?>';
				tempPlaylist = JSON.parse(tempSongIds);

				function playAlbum() {
					var firstSong = tempPlaylist[0];
					setTrack(firstSong, tempPlaylist, true);
				}
			</script>


		</ul>
	</div>
</section>
<div class="playlists-menu">
	<div class="menu-item">
		New Playlist
	</div>
	<div class="options-menu__divider"></div>
	<!-- placeholder for playlists -->
	<?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn); ?>
</div>
<div class="share-menu">
	<div 
		onclick="returnGooglePlusLink()"
		class="share-menu__item">
		<svg 
			class="linkedin-icon"
			aria-label="[title]"
			<title>Share this song</title>
			<use href="public/images/icomoon/sprite.svg#icon-google-plus-circle"></use>
		</svg>
		Google+
	</div>
	<div 
		onclick="returnPinterestLink()"
		class="share-menu__item">
		<svg 
			class="linkedin-icon"
			aria-label="[title]"
			<title>Share this song</title>
			<use href="public/images/icomoon/sprite.svg#icon-pinterest-p"></use>
		</svg>
		Pinterest
	</div>
	<div 
		onclick="returnTumblrLink()"
		class="share-menu__item">
		<svg 
			class="linkedin-icon"
			aria-label="[title]"
			<title>Share this song</title>
			<use href="public/images/icomoon/sprite.svg#icon-tumblr-square"></use>
		</svg>
		Tumblr
	</div>
	<div 
		onclick="returnFacebookLink()"
		class="share-menu__item">
		<svg 
			class="facebook-icon"
			aria-label="[title]"
			<title>Share this song</title>
			<use href="public/images/icomoon/sprite.svg#icon-facebook-square"></use>
		</svg>
		Facebook
	</div>
	<div 
		onclick="returnTwitterLink()"
		class="share-menu__item">
		<svg 
			class="twitter-icon"
			aria-label="[title]"
			<title>Share this song</title>
			<use href="public/images/icomoon/sprite.svg#icon-twitter-square"></use>
		</svg>
		Twitter
	</div>
	<div 
		onclick="returnLinkedInLink()"
		class="share-menu__item">
		<svg 
			class="linkedin-icon"
			aria-label="[title]"
			<title>Share this song</title>
			<use href="public/images/icomoon/sprite.svg#icon-linkedin-square"></use>
		</svg>
		LinkedIn
	</div>
	<div class="options-menu__divider"></div>
	<div 
		onclick="returnWebsiteLink()"
		class="share-menu__item">
		<svg 
			aria-label="[title]"
			<title>Share this song</title>
			<use href="public/images/icomoon/sprite.svg#icon-chain"></use>
		</svg>
		Copy Song Link
	</div>
</div>
<div class="options-menu">
	<div 
		onClick="goToArtist()"
		class="menu-item">
		Go to Artist
	</div>
	<div 
		onClick="goToAlbum()"
		class="menu-item">
		Go to Album
	</div>
	<div class="options-menu__divider"></div>
	<div 
		onClick="saveSong()"
		class="menu-item">
		Save Song
	</div>
	<div 
		id="open_playlists_menu" 
		class="menu-item">
		Add to playlist
		<svg 
			aria-label="[title]"
			<title>Add to playlist</title>
			<use href="public/images/icomoon/sprite.svg#icon-chevron-right"></use>
		</svg>
	</div>
	<div 
		onclick="removeFromPlaylist(<?php echo $playlist_id; ?>)"
		id="remove_pl_item" 
		class="menu-item">
		Remove from this Playlist
	</div>
	<div class="options-menu__divider"></div>
	<div 
		id="open_share_menu"
		class="menu-item">
		Share
		<svg 
			aria-label="[title]"
			<title>Share this song</title>
			<use href="public/images/icomoon/sprite.svg#icon-chevron-right"></use>
		</svg>
	</div>
</div>
