<?php
require_once '../utils/mysql.php';

class Adventures{
	
	protected $mysql;

	private $title;
	private $main_picture;
	private $county_id;
	private $score;
	private $description;

	function __construct($userid){
		$this->mysql = new MySQLClass();
		$this->getAllAdventures($userid);
	}

	static function getAllAdventuresAsArray($userid = ""){
		$queryString = "SELECT * FROM adventure";
		if(!empty($userid)){
			$queryString .= " WHERE user_id = {$userid}";
		}
		$id = intval($userid);
		$result = $this->mySQL->query("SELECT * FROM country WHERE id = {$id}");
		if($result->num_rows == 0) {
			return null;
		}
		$row = $result->fetch_assoc();
		$array = array();
		for($i = 0; $result->num_rows > $i; $i++){
			$array .= $row["title"] => array("user" => $row["user_id"], "main_picture" => $row["main_picture_id"], "description" => $row["description"], "country_id" => $row["country_id"]);
		}	
		
		return $array;
	}

	static function getAllAdventuresAsJson(){
		
	}

	static function getAdventureById(){
		
	}

}

?>