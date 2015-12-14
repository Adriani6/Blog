<?php
require_once '/../utils/mysql.php';

class Adventure{
	
	protected $mysql;

	private $adventureId;
	private $title;
	private $main_picture;
	private $country_id;
	private $score;
	private $description;
	
	

	function __construct($adventureId){
		$this->mysql = new MySQLClass();
		$this->load($adventureId);
	}
	
	public function load($adventureId) {
		$queryString = "SELECT * FROM adventure WHERE adventure_id = '{$adventureId}'";
		$result = $this->mysql->query($queryString);
		if($result->num_rows == 0) {
			return null;
		}
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
			$this->adventureId = $row['adventure_id'];
			$this->title = $row['title'];
			$this->main_picture = $row['main_picture_id'];
			$this->country_id = $row['country_id'];
			$this->description = $row['description'];
			$this->score = $row['score'];
			
		}		
	}
	
	/*
	function loadUsersAdventures($userid){
		$queryString = "SELECT * FROM adventure WHERE user_id = '{$useridd}'";
		$result = $this->mysql->query($queryString);
		if($result->num_rows == 0) {
			return null;
		}
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
			$this->adventureId = $row['adventure_id'];
			$this->title = $row['title'];
			$this->main_picture = $row['main_picture_id'];
			$this->country_id = $row['country_id'];
			$this->description = $row['description'];
			$this->score = $row['score'];
			
		}		
	}*/
	
	static function getAdventureFromID($id) {
		return new Adventure($id);
	}

	function getTitle(){
		return $this->title;
	}
	
	function getMainPicture(){
		return $this->main_picture;
	}
	
	function getCountryId(){
		return $this->country_id;
	}
	
	function getDescription(){
		return $this->description;
	}
	
	function getScore(){
		return $this->score;
	}
	
	function getAdventureId(){
		return $this->adventureId;
	}
	
	function addVote(){
		
	}
	
	function removeVote(){
		
	}

}

?>