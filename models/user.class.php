<?php
require_once '../utils/mysql.php';
require_once 'package.class.php';
require_once 'country.class.php';

class User{
	
	protected $salt = "Ct4adbUeU8";
	private $username;
	private $account_type;
	private $country;
	private $verified;
	private $comments;
	private $adventures;
	protected $mySQL;
	
	function __construct(){
		$this->mySQL = new MySQLClass();
		//$this->loadUserData();
	}
			
	static function isLoggedin(){
		if(!empty($username))
			return true;
		
		return false;
	}
	
	function getUsername(){
		return $this->username;
	}
	
	function isVerified(){
		if($this->verified === 1)
			return true;
		
		return false;
	}
	
	function loadUserData(){
		
		$data = $this->mySQL->query("SELECT * FROM users WHERE username = '". $this->username ."'");
		if($data->num_rows == 0) {
			return null;
		}
		$row = $data->fetch_assoc();
		$this->country = new Country((int)$row['country_id']);
		$this->verified = $row['verified'];
		$this->account_type = $row['type'];
		
	}
	
	function login($username, $password){

		$hashed = $this->mySQL->query("SELECT * FROM users WHERE username = '". $username ."'");

		$row = mysqli_fetch_row($hashed);


		if(password_verify($password . $this->salt, $row[2])){
			
			$this->username = $username;
			$this->loadUserData();
			return "Hello";
			/*if(!isset($_SESSION['user']) && !isset($_SESSION['token'])){
				#Set cookie with session logout time.
				$this->setCookieF($username);
							
				#Opens a new Session with global/session variables.
				$this->openSession($row[3], $username);	
				
				return "Hello";
			}*/

		}else{
			return "False";
		}		
	}
	
	function getCountry(){
		return $this->country;
	}
	
	static function register($username, $password, $name, $country){
		
	}
}

?>