<?php
require_once '/../utils/mysql.php';
require_once 'package.class.php';
require_once 'country.class.php';
require_once 'adventure.class.php';

/*
	
*/

class User extends Package{
		
	protected $salt = "Ct4adbUeU8";
	private $username;
	private $country;
	protected $sql;
	private $userid;
	private $adventures = array();
	
	function __construct($mysql, $username = "", $password = ""){
		$this->sql = $mysql;
		if(!empty($username) && !empty($password)){
			$this->login($username, $password);
		}
	}
			
	function isLoggedin(){
		if(isset($this->username)){
			return true;
		}
			
		
		return false;
	}
	
	function getUsernameFromID($id){
		$data = $this->sql->query("SELECT username FROM users WHERE user_id = '". $id ."'");
		if($data->num_rows == 0) {
			return null;
		}
		$row = $data->fetch_assoc();
		return $row['username'];
	}
	
	function getUsername(){
		return $this->username;
	}
	
	function isVerified(){
		if($this->verified === 1)
			return true;
		
		return false;
	}
		
	function login($username, $password){

		$hashed = $this->sql->query("SELECT * FROM users WHERE username = '". $username ."'");

		$row = mysqli_fetch_row($hashed);

		if(password_verify($password . parent::getSalt(), $row[2])){
			$this->username = $username;
			$this->loadUserData();
			return true;
		}else{
			return false;
		}		
	}
	
	function loadUserData(){
		$data = $this->sql->query("SELECT * FROM users WHERE username = '". $this->username ."'");
		if($data->num_rows == 0) {
			return null;
		}
		$row = $data->fetch_assoc();
		$this->country = new Country((int)$row['country_id']);
		$this->userid = $row['user_id'];
	}	
	
	function getUserId(){
		return $this->userid;
	}
	
	function getCountry(){
		return $this->country;
	}
	
	function findAdventure($adventureId){
		foreach($this->adventures as $adventure){
			if($adventure->getAdventureId() === $adventureId){
				return $adventure;
			}
		}
	}
	
	function getAdventures(){
		return $this->adventures;
	}
	
	function loadAdventures(){
		$smt = $this->sql->prepare("SELECT * FROM adventure WHERE user_id = ?");
		$smt->bindParam('s', $this->userid);
		$smt->execute();
		while($row = $smt->fetch()){
			
		}
		$adventureids = 4; 
		for($i = 1; $adventureids > $i; $i++){
			array_push($this->adventures, new Adventure($i));
		}
	}
	
	static function register($username, $password, $name, $country){
		
	}
	
	function getAccountType($sql){
		$data = $sql->query("SELECT type FROM users WHERE username = '{$this->username}'");
		//$data->bind_param('s', $this->username);
		//$data->execute();
		$row = $data->fetch_assoc();
		return $row['type'];
	}
	
	function setCountry($country){$this->country = $country;}
		
	function getSalt(){
		return $this->salt;
	}
}

?>