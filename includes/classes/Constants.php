<?php

class Constants {

	// error messages
	public static $error_un_len = 'Username must be between 5 and 25 characters';
	public static $error_un_taken = "Username already exists";
	public static $error_fn_len = "First name must be between 2 and 25 characters";
	public static $error_ln_len = "Last name must be between 2 and 25 characters";
	public static $error_em_match = "Your emails don't match";
	public static $error_em_valid = "Please enter a valid email";
	public static $error_em_taken = "Email already registered";
	public static $error_pw_match = "Your passwords don't match";
	public static $error_pw_valid = "Passwords can only contain letters and numebrs";
	public static $error_pw_len = "Password must be between 5 and 30 characters";
	public static $error_login_failed = "Your Username and/or Password was incorrect";
}
