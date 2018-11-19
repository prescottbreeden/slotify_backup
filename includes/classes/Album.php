<?php

class Album {

	private $con;
	private $id;
	private $title;
	private $system_id;
	private $artwork_path;
	private $year_released;

	public function __construct($con, $id) {
		$this->con = $con;
		$this->id = $id;

		$query = mysqli_query($this->con, "SELECT * FROM albums WHERE album_id='$this->id'");
		$album = mysqli_fetch_array($query);

		$this->title = $album['title_name'];
		$this->system_id = $album['system_id'];
		$this->artwork_path = $album['artwork_path'];
		$this->year_released = $album['year_released'];
	}

	public function getTitle() {
		return $this->title;
	}

	public function getYearReleased() {
		return $this->year_released;
	}

	public function getArtistObjects() {
		$query = mysqli_query($this->con, "
			 SELECT art.artist_id 
			   FROM album_artists AS aa 
					JOIN artists AS art
						ON aa.artist_id = art.artist_id 
					WHERE aa.album_id='$this->id'
		");
		$result = array();
		while($row = mysqli_fetch_array($query)) {
			array_push($result, new Artist($this->con, $row['artist_id']));
		}
		return $result;
	}
	
	public function getSystemId() {
		return $this->system_id;
	}

	public function getArtworkPath() {
		return $this->artwork_path;
	}
	
	public function getNumberOfSongs() {
		$query = mysqli_query($this->con, "
			 SELECT * 
			   FROM songs as s 
					WHERE s.album_id='$this->id'");

		return mysqli_num_rows($query);
	}
	
	public function getSongIds() {
		$query = mysqli_query($this->con, "
			 SELECT song_id 
			   FROM songs 
					WHERE album_id='$this->id' 
					ORDER BY album_order ASC");

		$array = array();
		while($row = mysqli_fetch_array($query)) {
			array_push($array, $row['song_id']);
		}
		return $array;
	}

	public function getTotalLength() {
		$query = mysqli_query($this->con, "
			 SELECT SEC_TO_TIME(SUM(duration)) AS duration
			   FROM songs 
					WHERE album_id='$this->id' 
					GROUP BY album_id
");
		$row = mysqli_fetch_array($query);
		return $row[0];
	}

}
?>

