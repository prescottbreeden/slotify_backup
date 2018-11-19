<?php

class User {

	private $con;
	private $id;
	private $username;
	private $first_name;
	private $laste_name;
	private $email;
	private $profile_pic;
	private $lp_album;
	private $lp_album_order;
	private $password;
	private $created_at;
	private $updated_at;

	public function __construct($con, $username) {
		$this->con = $con;
		$this->username = $username;

		$query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$this->username'");
		$user = mysqli_fetch_array($query);

		$this->id = $user['user_id'];
		$this->first_name = $user['first_name'];
		$this->last_name = $user['last_name'];
		$this->email = $user['email'];
		$this->profile_pic = $user['profile_pic'];
		$this->lp_album = $user['lp_album'];
		$this->lp_album_order = $user['lp_album_order'];
		$this->created_at = $user['created_date'];
		$this->updated_at = $user['updated_date'];
	}

	public function getId() {
		return $this->id;
	}

	public function getUsername() {
		return $this->username;
	}

	public function getFirstName() {
		return $this->first_name;
	}

	public function getLastName() {
		return $this->last_name;
	}

	public function getFullName() {
		return $this->first_name . ' ' . $this->last_name; 
	}

	public function getEmail() {
		return $this->email;
	}

	public function getProfilePic() {
		return $this->profile_pic;
	}

	public function getLastPlayedAlbum() {
		return $this->lp_album;
	}

	public function getLastPlayedAlbumOrder() {
		return $this->lp_album_order;
	}
}

?>
