<?php
require_once '../utils/mysql.php';

session_start();

class Package{

	protected $sql;
	//Add Salt.
	protected $salt;
	
	function __construct(){
		$sql = new MySQLClass();
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