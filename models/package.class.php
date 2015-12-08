<?php
require_once '../utils/mysql.php';

class Package{

	protected $sql;
	
	function __construct(){
		$sql = new MySQLClass();
	}
	
	static function getSalt(){
		return $this->salt;
	}

}
?>