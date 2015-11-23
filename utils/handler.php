<?php
require_once 'mysql.php';

session_start();

class Handler{
	
	protected $salt = "Ct4adbUeU8";
	protected $sql;
	
	function __construct(){
		$this->sql = new MySQL();
	}
	
	function login($username, $password){
		$hashed = $this->sql->query("SELECT * FROM users WHERE login = '". $username ."'");
		$row = mysqli_fetch_row($hashed);

		if(password_verify($password . $this->salt, $row[2])){
			$this->setCookieF($username);
			
			$passHash = password_hash($password . $this->salt, PASSWORD_DEFAULT);
			
			$this->openSession($row[3], $username, $passHash);
			header('Location: ../index.php');
				return true;
			}else{
				echo "Incorrect Credentials.";
			}
	}
	
	function register($username, $password){

		$pass = password_hash($password . $this->salt, PASSWORD_DEFAULT);
		$this->sql->query("INSERT INTO users (login, password) VALUES ('". $username ."', '". $pass ."')");
		if (mysql_errno() == 1062) {
			echo "Username already taken.";
		}
		
	}
	
	function logOut(){
		session_destroy();
	}
		
	function setCookieF($name){
		setcookie("blog", $name, time() + (86400 * 30), "/");
	}
	
	function openSession($type, $user, $hash){
		$_SESSION['user'] = $user;
		$_SESSION['hash'] = $hash;
		if(isset($type)){
			$_SESSION['type'] = $type;
		}
	}
	
}
?>