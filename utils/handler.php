<?php

#Requires the MySQL Class, so connection can be established.
require_once 'mysql.php';
require_once 'support_functions.php';

$mySQL = new MySQLClass();

#Starts a session by default on the site.
session_start();

/*
	Handler Class, responsible for handling user account options, such as logging in/out, registering, checking sessions/cookies.
	The calss is also responsible for handling data back.
	
	Most of the stuff in this class is requested from requests.php, which handles accessing of functions in this class.
*/

class Handler{
	
	#Salt, the protected type disallows it to be accessed outside this class.

	#sql, MySQL connection, set in constructor.
	protected $sql;
	# registration attempt result
	protected $registrationResult = '';

	public function getRegistrationResult() {
		return $this->registrationResult;
	}

	#Constructor for the class.
	function __construct(){
		#Opens new MySQL connection to the blog database.
		$this->sql = new MySQLClass();
	}
	
	#Login handles the verification of hashs.
	function login($username, $password){
		// Now user.class.php
		#Query the Database for users details.
		$hashed = $this->sql->query("SELECT * FROM users WHERE username = '". $username ."'");
		#Gets the whole row from MySQL Results.
		$row = mysqli_fetch_row($hashed);

		#Verifies that both passwords are the same (By checking the hashs)
		if(password_verify($password . $this->salt, $row[2])){
			#The passwords match.
			
			if(!isset($_SESSION['user']) && !isset($_SESSION['token'])){
				#Set cookie with session logout time.
				$this->setCookieF($username);
							
				#Opens a new Session with global/session variables.
				$this->openSession($row[3], $username);	
			}

		}else{
			return false;
		}	
	}

	function register($username, $password, $cppassword, $name, $country){
		if (empty($username)){
			$this->registrationResult .= "Username field is required.<br>";
		}
		else if(strlen($username) < 3 ) {
			$this->registrationResult .= "Username must be at least 6 characters long.<br>";
		}
		else if(strlen($username) > 50 ) {
			$this->registrationResult .= "Username can be maximum 50 characters long.<br>";
		}
        else if($this->sql->selectUser($username)) {
            $this->registrationResult .= "Username is already taken<br>";
        }

		if(empty($password)) {
			$this->registrationResult .= "Password field is required.\n";
		}
		else if(strlen($password) < 6 ) {
			$this->registrationResult .= "Password must be at least 6 characters long.<br>";
		}
		else if(strlen($password) > 50 ) {
			$this->registrationResult .= "Password cannot be longer than 50 characters.<br>";
		}

		if(!preg_match("/^[a-zA-Z1-9]*$/",$username)){
			$this->registrationResult .= "Username can only contain letters and digits.";
		}

		if($password != $cppassword){
			$this->registrationResult .= "Passwords do not match.";
		}

        //echo "<script type='text/javascript'>alert('".$this->registrationResult."');</script>";

		if($this->registrationResult != ''){
            //echo "<script type='text/javascript'>alert('yo');</script>";
			return 0;
		}

		$hash = password_hash($password . $this->salt, PASSWORD_DEFAULT);
        $name = polish($name);
        $country = polish($country);
		$country = $this->sql->getMysqli()->real_escape_string($country);

        if($id = getCountryID($country,$this->sql) == null) {
            $this->registrationResult .= "An error occurred. Please try again later.";
            return 0;
        }

		$this->sql->query("INSERT INTO users (username, password, name, country_id)
                           VALUES ('". $username ."', '". $hash ."','".$name."', ".(int)$id.")");
	}
	
	function logOut(){
		session_destroy();
		header("Location: ../index.php");
	}
		
	function setCookieF($name){
		setcookie("blog".$name, $name, time() + (86400 * 30), "/");
	}
	
	function deleteCookieF($name){
		setCookie("blog".$name, "", time() - 3600);
	}
	
	function checkCookie($name){
		if(isset($_COOKIE['blog_'.$name])){
			if($_COOKIE['expiry'] < time()){
				$this->deleteCookieF($name);
				$this->logOut();
			}
		}
	}
	
	function openSession($type, $user){
		$_SESSION['user'] = $user;
		$_SESSION['token'] = $this->generateToken();
		if(isset($type)){
			$_SESSION['type'] = $type;
		}
	}
	
	function generateToken(){
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
	}

	// Removes additional white spaces and takes care of all unsafe characters
	// Use to validate data from forms
}
?>