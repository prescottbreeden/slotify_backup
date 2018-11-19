<?php
include('includes/includedFiles.php');

if(isset($_SESSION['userLoggedIn'])) {
	$userLoggedIn = $_SESSION['userLoggedIn'];
	echo "<script>userLoggedIn = '$userLoggedIn';</script>";
	$user = new User($con, $userLoggedIn);
	$user_id = $user->getId();

}
else {
	header("Location: register.php");
}

$playlistQuery = mysqli_query($con, "
 SELECT p.name,
		p.playlist_id,
		p.user_id,
		p.created_at,
		p.updated_at,
		u.username
   FROM playlists AS p 
		JOIN users AS u
			ON p.user_id=u.user_id
		WHERE u.user_id='$user_id'
");

$songsQuery = mysqli_query($con, "
 SELECT s.song_id 
   FROM songs AS s
		JOIN saved_songs AS ss
			ON s.song_id = ss.song_id
		WHERE user_id = '$user_id'
		ORDER BY created_at
");

$albumQuery = mysqli_query($con, "
 SELECT a.album_id,
		a.title_name, 
		a.artwork_path
   FROM albums as a
		JOIN saved_albums as sa
			ON a.album_id = sa.album_id
		WHERE sa.user_id='$user_id'
");

?>

<section class="playlists">
	<div class='album__header'>
		<div class='album__header--artwork'>
			<div class="profile__image">
				<img src="<?php echo $user->getProfilePic(); ?>" alt="profile image">
			</div>
		</div>
		<div class='album__header--details'>
			<div class='album__header--details-miniheader'>
				Your Music
			</div>
			<div class="profile__fullname">
				<?php echo $user->getFullName(); ?>
			</div>
		</div>
	</div>
		<h2 class="u-secondary-heading u-nudge-down">Playlists</h2>
		<div class="u-divider"></div>
		<div 
			onclick="createPlaylist()"
			class="playlists__btn">
			New Playlist	
		</div>
		<div class="playlists__container">

		<?php
		if(mysqli_num_rows($playlistQuery) == 0) {
			echo "<span class='playlists__noresults'>You haven't created any playlists yet.</span>"	;
		}
		while($row = mysqli_fetch_array($playlistQuery)) {
			echo	"<div class='playlists__item'>
						<span 
							role='link'
							tabindex='0'
							onclick='openPage(\"playlist.php?id=" . $row['playlist_id'] . "\")'>
							<div class='playlist__header--artwork'>
								<svg class='playlist__icon'>
									<use href='public/images/icomoon/sprite.svg#icon-videogame_asset'</use>
								</svg>	
								<div class='playlist__header--artwork-hover'>
									<svg 
										onclick='jumpToPlaylist(" . $row['playlist_id'] . ")'
										class='playlist__icon-hover'>
										<use href='public/images/icomoon/sprite.svg#icon-play2'</use>
									</svg>	
								</div>
							</div>
							<div class='album-select__container--item-details'>
								<div class='album-select__container--item-title'>	
									" . $row['name'] . "
								</div>
							</div>
						</span>
					</div>";
		}
		?>
		</div>
	<h2 class="u-secondary-heading">Songs</h2>
	<div class="u-divider"></div>
	<div class='tracks'>
		<div class='tracks__list'>

			<?php 
			if(mysqli_num_rows($songsQuery) === 0) {
				echo "<span class='noResults'>You do not have any saved songs in your library</span>";
			}
			else {
				echo "
					<div class='tracks__list--item'>
						<div class='tracks__list--number-header'>#</div>
						<div class='tracks__list--name-header'>Title</div>
						<div class='tracks__list--artist-header'>artist</div>
						<div class='tracks__list--artist-header'>album</div>
						<div class='tracks__list--more'></div>
						<div class='tracks__list--duration'>
							<svg 
								class='tracks__list--duration-header'
								aria-label='[title]'
								<title>Duration</title>
								<use href='public/images/icomoon/sprite.svg#icon-clock'></use>
							</svg>

						</div>

					</div>
				";
			}
			$song_array = array();
			$i = 1;
			while($row = mysqli_fetch_array($songsQuery)) {
				if($i > 15) {
					break;
				}
				array_push($song_array, $row['song_id']);
				$song = new Song($con, $row['song_id']);
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

<!-- dropdown menus --> 
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
	<div 
		onclick="goToAlbum()"	
		class="menu-item">
		Go to Album
	</div>
	<div class="options-menu__divider"></div>
	<div 
		onClick="saveToLibrary()"
		class="menu-item">
		Remove from Your Music
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
