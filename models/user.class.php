<?php
require_once '../utils/mysql.php';
require_once 'package.class.php';
require_once 'country.class.php';
require_once 'adventure.class.php';

class User extends Package{
	
	protected $salt = "Ct4adbUeU8";
	
	private $username;
	private $userid;
	private $account_type;
	private $country;
	private $verified;
	private $comments;
	private $adventures = array();
	
	function __construct($username = ""){
		if(!empty($username)){
			$this->username = $username;
			$this->loadUserData();
		}
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
		
		$data = parent::$sql->query("SELECT * FROM users WHERE username = '". $this->username ."'");
		if($data->num_rows == 0) {
			return null;
		}
		$row = $data->fetch_assoc();
		$this->country = new Country((int)$row['country_id']);
		$this->verified = $row['verified'];
		$this->account_type = $row['type'];
		$this->userid = $row['user_id'];
		$this->loadAdventures();
	}
	
	function login($username, $password){

		$hashed = $this->mySQL->query("SELECT * FROM users WHERE username = '". $username ."'");

		$row = mysqli_fetch_row($hashed);


		if(password_verify($password . $this->salt, $row[2])){
			
			$this->username = $username;
			$this->loadUserData();
			
			if(!isset($_SESSION['user']) && !isset($_SESSION['token'])){
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
		
		$adventureids = 4; //Adventures::getAdventureIds($this->getUserId());
		for($i = 1; $adventureids > $i; $i++){
			array_push($this->adventures, new Adventure($i));
		}
	}
	
	static function register($username, $password, $name, $country){
		
	}
	
	function setUsername($username){$this->username = $username;}
	function setUserId($userid){$this->userid = $userid;}
	function setAccountType($type){$this->account_type = $type;}
	function setCountry($country){$this->country = $country;}
	function setVerified($v){$this->verified = $v;}
	function setComments($c){$this->comments = $c;}
	
	function setUserData($userid, $account_type, $country, $verified = "", $comments = ""){
		if(isset($username, $userid, $account_type, $country, $verified, $comments)){
			//Write Setters... fetch data the way loadData() works and remove that function.
			//$this->setUsername($username);
			$this->setUserId($userid);
			$this->setAccountType($account_type);
			$this->setCountry(new Country($country));
			$this->setVerified($verified);
			$this->setComments($comments);
		}else{
			return false;
		}
	}
}

?>