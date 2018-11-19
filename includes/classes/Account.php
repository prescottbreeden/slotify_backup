<?php

class Account {

	private $con;
	private $user_id;
	private $username;
	private $errorArray;

	public function __construct($con) {
		$this->con = $con;
		$this->errorArray = array();
		
	}

	public function login($un, $pw) {
		$pw = md5($pw);
		$query = mysqli_query($this->con, "SELECT * FROM users WHERE username = '$un' AND password='$pw'");

		if(mysqli_num_rows($query) == 1) {
			return true;
		}
		else {
			array_push($this->errorArray, Constants::$error_login_failed);
			return false;
		}
	}


	public function register($un, $fn, $ln, $em, $em2, $pw, $pw2) {
		$this->validateUsername($un);
		$this->validateFirstName($fn);
		$this->validateLastName($ln);
		$this->validateEmails($em, $em2);
		$this->validatePasswords($pw, $pw2);

		if(empty($this->errorArray)) {
			// Insert into DB
			return $this->insertUserDetails($un, $fn, $ln, $em, $pw);
		}
		else {
			return false;
		}
	}

	public function getError($error) {
		if(!in_array($error, $this->errorArray)) {
			$error = "";
		}
		return "<span class='errorMessage'>$error</span>";
	}

	private function insertUserDetails($un, $fn, $ln, $em, $pw) {
		$encryptedPw = md5($pw);
		$profilePic = "public/images/profile-pics/head_emerald.png";
		$result = mysqli_query($this->con, "INSERT INTO users 
			(username, first_name, last_name, email, password, profile_pic) 
			VALUES ('$un', '$fn', '$ln', '$em', '$encryptedPw', '$profilePic')");

		return $result;
	}

	private function validateUsername($un) {
		if(strlen($un) > 25 || strlen($un) < 5) {
			array_push($this->errorArray, Constants::$error_un_len);
			return;
		}

		$checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username='$un'");
		if(mysqli_num_rows($checkUsernameQuery) != 0) {
			array_push($this->errorArray, Constants::$error_un_taken);
		}

	}

	private function validateFirstName($fn) {
		if(strlen($fn) > 25 || strlen($fn) < 2) {
			array_push($this->errorArray, Constants::$error_fn_len);
			return;
		}
	}

	private function validateLastName($ln) {
		if(strlen($ln) > 25 || strlen($ln) < 2) {
			array_push($this->errorArray, Constants::$error_ln_len);
			return;
		}
	}

	private function validateEmails($em, $em2) {
		if($em != $em2) {
			array_push($this->errorArray, Constants::$error_em_match);
			return;
		}

		if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
			array_push($this->errorArray, Constants::$error_em_valid);
			return;
		}

		$checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email='$em'");
		if(mysqli_num_rows($checkEmailQuery) != 0) {
			array_push($this->errorArray, Constants::$error_em_taken);
		}
	}

	private function validatePasswords($pw, $pw2) {
		if($pw != $pw2) {
			array_push($this->errorArray, Constants::$error_pw_match);
			return;
		}

		if(preg_match('/[^A-Za-z0-9]/', $pw)) {
			array_push($this->errorArray, Constants::$error_pw_valid);
			return;
		}
		if(strlen($pw) > 30 || strlen($pw) < 5) {
			array_push($this->errorArray, Constants::$error_pw_len);
			return;
		}

	}
}

?>
