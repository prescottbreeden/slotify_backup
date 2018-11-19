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
	$albumId = $_GET['id'];
} 
else {
	header("Location: index.php");
}

$album = new Album($con, $albumId);
$user = new User($con, $userLoggedIn);
$userId = $user->getId();
$artist_array = $album->getArtistObjects();
$artwork_path = $album->getArtworkPath();
$album_name =  $album->getTitle();
$total_songs = $album->getNumberOfSongs();
$total_length = $album->getTotalLength();
$year_released = $album->getYearReleased();

$query = mysqli_query($con, "
		 SELECT *
		   FROM saved_albums
				WHERE album_id='$albumId'
				AND user_id='$userId'
");

if(mysqli_num_rows($query) > 0) {
	$album_saved = true;
} else {
	$album_saved = false;
}

function formatTime($str) {
	$formatted;
	$duration = explode(":", $str);
	$hours = $duration[0];
	$minutes = $duration[1];
	$hours = number_format($hours);
	$minutes = number_format($minutes);
	if($hours > 0) {
		$formatted = $hours . 'hr ' . $minutes . 'min';
	} else {
		$formatted = $minutes . 'min';
	}
	return $formatted;
}

function formatDuration($str) {
	$formatted;
	$duration = explode(":", $str);
	$hours = number_format($duration[0]);
	$minutes = number_format($duration[1]);
	$seconds = $duration[2];
	if($hours > 0) {
		$formatted = "$hours : $minutes : $seconds";
	}
	else {
		$formatted = "$minutes:$seconds";
	}
	return $formatted;
}

?>
<section class='album'>
	<div class='album__header'>
		<div class='album__header--artwork'>
			<img src="public/images/artwork/<?php echo $artwork_path; ?>" alt='album art'>
			<div class="album__header--artwork-hover">
				<svg 
					onclick="playAlbum()";
					class='playlist__icon-hover'>
					<use href='public/images/icomoon/sprite.svg#icon-play2'</use>
				</svg>	
			</div>
		</div>
		<div class='album__header--details'>
			<div class='album__header--details-miniheader'>
				<?php echo ($album_saved ? 'Album from your music' : 'Album'); ?> 
			</div>
			<div class='album__header--details-title'>
				<?php echo $album_name; ?> 
			</div>
			<div class='album__header--details-artist'>
				<span class="album__header--details-artist-by">By</span>
				<?php
				foreach($artist_array as $artist) {
					echo "
						<span 
							onclick='openPage(\"artist.php?id=" . $artist->getId() . "\")'
							class='album__header--details-artist-name'>
							" . $artist->getName() . "
						</span>
					";
				}
				?>
			</div>
			<div class='album__header--misc'>
				<?php echo $year_released; ?> &bull;
				<?php echo $total_songs; ?> songs, 
				<?php echo formatTime($total_length) ?>
			</div>
			<div class='album__btn'>
				<div 
					onclick="playAlbum()"
					class='album__btn--play'>
					<p>Play</p>
				</div>
				<div 
					onclick="addAlbumToSaved(<?php echo $albumId; ?>)"
					class='album__btn--save'>
					<p><?php echo ($album_saved ? 'Saved' : 'Save'); ?> </p>
				</div>
				<div 
					onclick="showAlbumMenu()"
					class="album__btn--more">
					<div 
						onclick="removeAlbumFromSaved(<?php echo $albumId; ?>)"
						id="album_menu"
						class="album-menu__item">
						Remove from your music
					</div>
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
				<div class='tracks__list--more'></div>
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
			$song_array = $album->getSongIds();
			$i = 1;
			foreach($song_array as $song) {
				$song = new Song($con, $song);
				$songArtist = $song->getArtistObject();
				$duration = $song->getDuration();
				$duration = formatDuration($duration);
				//artist object

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
						<div class='tracks__list--duration'>" . $duration . "</div>
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
</section>

<div class="playlists-menu">
	<div 
	onclick="createPlaylist()"
		class="menu-item">
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
		onclick="goToArtist()"	
		class="menu-item">
		Go to Artist
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
