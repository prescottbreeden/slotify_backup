<?php

class Artist {

	private $con;
	private $id;
	private $name;

	public function __construct($con, $id) {
		$this->con = $con;
		$this->id = $id;

		$artistQuery = mysqli_query($this->con, "SELECT * FROM artists WHERE artist_id='$this->id'");
		$artist = mysqli_fetch_array($artistQuery);

		$this->name = $artist['name'];
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getSongIds() {
		$query = mysqli_query($this->con, "
			 SELECT song_id 
			   FROM songs 
					WHERE artist_id='$this->id' 
					ORDER BY play_count DESC");

		$array = array();
		while($row = mysqli_fetch_array($query)) {
			array_push($array, $row['song_id']);
		}
		return $array;
	}

}
?>

