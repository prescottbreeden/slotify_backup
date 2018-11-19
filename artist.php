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
	$artistId = $_GET['id'];
}
else {
	header("Location: index.php");
}

if(isset($_GET['term'])) {
	$term = urldecode($_GET['term']);
}
else {
	$term = '';
}

$artist = new Artist($con, $artistId);

?>
<section class="artist">
	<div class='album__header'>
		<div class='album__header--artwork'>
			<div class="profile__image">
				<img src="public/images/profile-pics/head_emerald.png" alt="profile image">
			</div>
		</div>
		<div class='album__header--details'>
			<div class='album__header--details-miniheader'>
				Artist
			</div>
			<div class="profile__fullname">
				<?php echo $artist->getName(); ?>
			</div>
			<div class="artist__header-btn">
				<div 
					onclick="playAlbum()" 
					style="margin: 1rem auto;"
					class="artist__header-btn--btn">
						Play
				</div>
			</div>
		</div>
	</div>

	<div class='tracks'>
		<h2 class="u-secondary-heading">Popular</h2>
		<div class='tracks__list'>
			<div class="tracks__list--item">
				<div class="tracks__list--number-header">#</div>
				<div class="tracks__list--name-header">Title</div>
				<div class="tracks__list--artist-header">album</div>
				<div class='tracks__list--more'></div>
				<div class="tracks__list--duration"></div>

			</div>

			<?php 
			$song_array = $artist->getSongIds();
			$i = 1;
			foreach($song_array as $song) {
				if($i > 5) {
					break;
				}
				$song = new Song($con, $song);
				$songArtist = $song->getArtistObject();
				$playcount = $song->getPlayCount();
				$formattedPlayCount = number_format($playcount);

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
							onclick='openPage(\"album.php?id=" . $song->getAlbumId() . "\")'
							class='tracks__list--artist'>" . $song->getAlbumName() . "
						</div>
						<div class='tracks__list--more'>
							<input type='hidden' class='songId' value='" . $song->getId() . "'>
							<input type='hidden' class='albumId' value='" . $song->getAlbumId() . "'>
							<input type='hidden' class='artistId' value='" . $song->getArtistId() . "'>
							<svg 
								class='options__button'
								onclick='showOptionsMenu(this)' 
								aria-label='[title]'
								<title>More</title>
								<use href='public/images/icomoon/sprite.svg#icon-more-horizontal'></use>
							</svg>
						</div>
						<div class='tracks__list--duration'>" . $formattedPlayCount . "</div>
					</div>

					";

				$i = $i+1;
			}

			?>

			<script>
				var tempSongIds = '<?php echo json_encode($song_array); ?>';
				tempPlaylist = JSON.parse(tempSongIds);

				function playAlbum() {
					var firstSong = tempPlaylist[0];
					setTrack(firstSong, tempPlaylist, true);
				}
			</script>

		</ul>
	</div>
	<h2 class="u-secondary-heading">Albums</h2>
	<div class="u-divider"></div>
	<div class="album-select__container">
<?php
$albumQuery = mysqli_query($con, "
  SELECT al.album_id,
		 al.title_name, 
		 al.artwork_path
	FROM albums as al 
		JOIN album_artists AS aa
			ON aa.album_id = al.album_id  
		 WHERE aa.artist_id = '$artistId'");

while($row = mysqli_fetch_array($albumQuery)) {

	echo	"<div class='album-select__container--item'>
				<span 
					role='link'
					tabindex='0'
					onclick='openPage(\"album.php?id=" . $row['album_id'] . "\")'>
					<img src='public/images/artwork/" . $row['artwork_path'] . "'>	
					<div class='album-select__container--item-details'>
						<div class='album-select__container--item-title'>	
							" . $row['title_name'] . "
						</div>
					</div>
				</span>
			</div>";
}

?>
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
	<div class="options-menu__divider"></div>
	<div 
		onclick="goToAlbum()"
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
	<div class="menu-item">
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
