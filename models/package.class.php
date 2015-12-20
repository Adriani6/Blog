<?php
if(count(get_included_files()) ==1) exit("Access denied");

require_once '/../utils/mysql.php';
require_once 'user.class.php';

class Package{

	protected $sql;
	//Add Salt.
	protected $salt = "Ct4adbUeU8";
	//private $user;
	
	function __construct(){
		$this->sql = new MySQLClass();
	}
	
	function getSalt(){
		return $this->salt;
	}
	
	
	static function sendQuery($query){
		
		//Create prepared statements
		$this->$sql->query($query);
	}

}
?>