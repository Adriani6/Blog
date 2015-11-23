<?php
require_once 'mysql.php';

protected hash;

session_start();

class Handler{
	
	protected $salt = "Ct4adbUeU8";
	protected $sql;
	
	function __construct(){
		$this->sql = new MySQL();
	}
	
	function login($username, $password){
		if(password_verify($password . $salt, $hashed)){
			$this->hash = $
				return true;
			}else{
				return false;
			}
	}
	
	function register($username, $password){

		$pass = password_hash($password . $this->salt, PASSWORD_DEFAULT);
		$this->sql->query("INSERT INTO users (login, password) VALUES ('". $username ."', '". $pass ."')");
		if (mysql_errno() == 1062) {
			echo "Username already taken.";
		}
		
	}
	
	function setCookie(){

	}
	
}
?>