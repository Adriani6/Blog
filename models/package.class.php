<?php
require_once '../utils/mysql.php';
require_once 'user.class.php';

session_start();

class Package{

	protected $sql;
	//Add Salt.
	protected $salt;
	private $user;
	
	function __construct(){
		$this->sql = new MySQLClass();
		$this->user = new User();
	}
	
	static function getSalt(){
		return $this->salt;
	}
	
	
	static function sendQuery($query){
		
		//Create prepared statements
		$this->$sql->query($query);
	}

}
?>