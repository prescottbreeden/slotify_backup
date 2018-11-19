<?php

class Playlist {

	private $con;
	private $id;
	private $name;
	private $owner_id;
	private $owner_name;
	private $created_at;
	private $updated_at;

	public function __construct($con, $id) {
		$this->con = $con;
		$this->id = $id;

		$query = mysqli_query($this->con, "
			 SELECT p.name,
					p.user_id,
					u.username,
					p.created_at,
					p.updated_at
			   FROM playlists as p
					JOIN users as u
						ON u.user_id = p.user_id
					WHERE playlist_id='$this->id'
			");
		$playlist = mysqli_fetch_array($query);

		$this->name = $playlist['name'];
		$this->owner_id = $playlist['user_id'];
		$this->owner_name = $playlist['username'];
		$this->created_at = $playlist['created_at'];
		$this->updated_at = $playlist['updated_at'];

	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getOwnerId() {
		return $this->owner_id;
	}

	public function getOwnerName() {
		return $this->owner_name;
	}

	public function getCreatedAt() {
		return $this->created_at;
	}

	public function getUpdatedAt() {
		return $this->updated_at;
	}

	public function getNumberOfSongs() {
		$query = mysqli_query($this->con, "
			 SELECT song_id 
			   FROM pl_songs 
					WHERE playlist_id='$this->id'");

		return mysqli_num_rows($query);
	}
	
	public function getSongIds() {
		$query = mysqli_query($this->con, "
			 SELECT song_id
			   FROM pl_songs 
					WHERE playlist_id='$this->id' 
					ORDER BY playlist_order ASC");

		$array = array();
		while($row = mysqli_fetch_array($query)) {
			array_push($array, $row['song_id']);
		}
		return $array;
	}

	public function getPlaylistOrder() {
		$query = mysqli_query($this->con, "
			 SELECT playlist_order
			   FROM pl_songs 
					WHERE playlist_id='$this->id' 
					ORDER BY playlist_order ASC");

		$array = array();
		while($row = mysqli_fetch_array($query)) {
			array_push($array, $row['playlist_order']);
		}
		return $array;
	}

	public function getTotalLength() {
		$query = mysqli_query($this->con, "
			 SELECT 
					CASE
						WHEN TIME_FORMAT(SUM(duration), '%i') > 0 
							THEN TIME_FORMAT(SUM(duration), '%i')
						ELSE TIME_FORMAT(SUM(duration)-60, '%i')+1
					END AS duration
			   FROM songs AS s
					JOIN pl_songs AS p
						ON s.song_id = p.song_id
					WHERE playlist_id='$this->id' 
					GROUP BY playlist_id
		");

		$row = mysqli_fetch_array($query);
		return $row[0];
	}

	public static function getPlaylistsDropdown($con, $username) {
		$dropdown = '';
		$user = new User($con, $username);
		$userId = $user->getId();

		$query = mysqli_query($con, "
			 SELECT playlist_id, 
					name 
			   FROM playlists 
					WHERE user_id='$userId'");
		
		while($row = mysqli_fetch_array($query)) {
			$name = $row['name'];
			$id = $row['playlist_id'];

			$dropdown = $dropdown . "
				<input type='hidden' class='playlistId' value='" . $id . "'>
				<div 
					onclick=''
					class='menu-item playlist-item'>
					" . $name . "
				</div>
			";
		}

		return $dropdown;
	}
}

?>
