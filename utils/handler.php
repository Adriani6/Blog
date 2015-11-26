<?php

#Requires the MySQL Class, so connection can be established.
require_once 'mysql.php';

#Starts a session by default on the site.
session_start();

/*
	Handler Class, responsible for handling user account options, such as logging in/out, registering, checking sessions/cookies.
	The calss is also responsible for handling data back.
	
	Most of the stuff in this class is requested from requests.php, which handles accessing of functions in this class.
*/

class Handler{
	
	#Salt, the protected type disallows it to be accessed outside this class.
	protected $salt = "Ct4adbUeU8";
	#sql, MySQL connection, set in constructor.
	protected $sql;
	
	#Constructor for the class.
	function __construct(){
		#Opens new MySQL connection to the blog database.
		$this->sql = new MySQL();
	}
	
	#Login handles the verification of hashs.
	function login($username, $password){
		#Query the Database for users details.
		$hashed = $this->sql->query("SELECT * FROM users WHERE login = '". $username ."'");
		#Gets the whole row from MySQL Results.
		$row = mysqli_fetch_row($hashed);

		#Verifies that both passwords are the same (By checking the hashs)
		if(password_verify($password . $this->salt, $row[2])){
			#The passwords match.
			
			#Set cookie with session logout time.
			$this->setCookieF($username);
						
			#Opens a new Session with global/session variables.
			$this->openSession($row[3], $username);
				#Return true
				return true;

			}else{
				header("Location: ../login.php?err=authError");
			}
	}
	
	function register($username, $password){

		$pass = password_hash($password . $this->salt, PASSWORD_DEFAULT);
		$this->sql->query("INSERT INTO users (login, password) VALUES ('". $username ."', '". $pass ."')");
		header("Location: ../index.php");
		if (mysql_errno() == 1062) {
			echo "Username already taken.";
		}
		
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
	
}
?>