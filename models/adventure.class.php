<?php
require_once '/../utils/mysql.php';
require_once 'country.class.php';
require_once 'user.class.php';

class Adventure extends Country{
	
	protected $mysql;

	private $adventureId;
	private $title;
	private $main_picture;
	private $country_id;
	private $score;
	private $description;
	
	

	function __construct($mysql, $adventureId = ""){
		$this->mysql = $mysql;
		if(!empty($adventureId)){
			$this->load($adventureId);
		}
	}
	
	function countAdventures(){
		$result = $this->mysql->query("SELECT adventure_id FROM adventure");
		
		return mysqli_num_rows($result);
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
	
	
	function addVote(){
		
	}
	
	function removeVote(){
		
	}
	
	function getOffsetAdventures($offset){
		$result = $this->mysql->query("SELECT * FROM adventure LIMIT 5 OFFSET {$offset}");

		if($result->num_rows > 0) {
			$adventure = array();
			
		while($row = mysqli_fetch_array($result)){
			
			$holder = array('Title' => $row['title'], 'Country' => $row['country_id'], 'ID' => $row['adventure_id'], 'User' => $row['user_id'], 'ShortDescription' => $this->createShortDescription($row['description']), 'Description' => $row['description'], 'MainPicture' => $this->getPictureFilename($row['main_picture_id']), 'Images' => $this->getAdventurePictures($row['adventure_id']));
			//array_push($holder, 'Title' => $row[0], 'Description' => $row['description'], 'Country' => getCountryName($row['country_id'],$this->mysql));
			array_push($adventure, $holder);
			//$adventure['main_picture'] = getPictureFilename($row['main_picture_id'],$this->mysql);
		}

			//$title = $adventure['title'];
			return $adventure;
		}
		else
			return null;	
	}
	
	function getLastFiveAdventures() {
    $result = $this->mysql->query("SELECT * FROM adventure ORDER BY adventure_id DESC LIMIT 5");
	if(!$result){
			die('Could not query:' . $this->mysql->error);
		}

    if($result->num_rows > 0) {
		$adventure = array();
		
	while($row = mysqli_fetch_array($result)){
		
		$holder = array('Title' => $row['title'], 'Country' => $row['country_id'], 'ID' => $row['adventure_id'], 'User' => $row['user_id'], 'ShortDescription' => $this->createShortDescription($row['description']), 'Description' => $row['description'], 'MainPicture' => $this->getPictureFilename($row['main_picture_id']), 'Images' => $this->getAdventurePictures($row['adventure_id']));
        //array_push($holder, 'Title' => $row[0], 'Description' => $row['description'], 'Country' => getCountryName($row['country_id'],$this->mysql));
		array_push($adventure, $holder);
        //$adventure['main_picture'] = getPictureFilename($row['main_picture_id'],$this->mysql);
	}

        //$title = $adventure['title'];
        return $adventure;
    }
    else
        return null;
	}
	
	function getAdventure ($id) {
		$result = $this->mysql->query("SELECT * FROM adventure WHERE adventure_id={$id}");

		if($result->num_rows == 1) {
			$row = $result->fetch_assoc();

			$adventure['username'] = User::getUsernameById($row['user_id'], $this->mysql);
			$adventure['title'] = $row['title'];
			$adventure['description'] = $row['description'];
			$adventure['country'] = parent::getCountryNameById($row['country_id'],$this->mysql);
			$adventure['main_picture'] = $this->getPictureFilename($row['main_picture_id'],$this->mysql);

			$result = $this->mysql->query("SELECT picture_id,name FROM picture where adventure_id={$id}");
			$i = 0;
			while($picture = $result->fetch_assoc()){
				if($picture['picture_id'] != $row['main_picture_id'])
					$adventure['picture'][$i] = "./uploads/".$picture{'name'};
				$i++;
			}

			$result = $this->mysql->query("SELECT value FROM tags where adventure_id={$id}");
			$i = 0;
			while($tag = $result->fetch_assoc()){
				$adventure['tag'][$i] = $tag["value"];
				$i++;
			}

			$title = $adventure['title'];
			return $adventure;
		}
		else
			return null;
	}
	
	function getUsersAdventures($userid){
	    $result = $this->mysql->query("SELECT * FROM adventure WHERE user_id = {$userid}");
		if(!$result){
				die('Could not query:' . $this->mysql->error);
			}

		if($result->num_rows > 0) {
			$adventure = array();
			
		while($row = mysqli_fetch_array($result)){
			
			$holder = array('Title' => $row['title'], 'Country' => $row['country_id'], 'ID' => $row['adventure_id'], 'User' => $row['user_id'], 'ShortDescription' => $this->createShortDescription($row['description']), 'Description' => $row['description'], 'MainPicture' => $this->getPictureFilename($row['main_picture_id']), 'Images' => $this->getAdventurePictures($row['adventure_id']));
			
			array_push($adventure, $holder);

		}

			return $adventure;
		}
		else
			return null;	
	}
	
	function getAdventurePictures($id){
		$result = $this->mysql->query("SELECT picture_id,name FROM picture where adventure_id={$id}");
		$images = array();

		while($picture = $result->fetch_assoc()){
			//if($picture['picture_id'] != $row['main_picture_id'])
				array_push($images, "./uploads/".$picture{'name'});
		}	
		return $images;
	}
	
	function getPictureFilename($id,$mySQL="") {
		$id = intval($id);
		$mysql = $this->mysql;
		if(!empty($mySQL)){$mysql = $mySQL;}
		$result = $mysql->query("SELECT name FROM picture WHERE picture_id={$id}");
		if($result->num_rows == 0) {
			return null;
		}

		$row = $result->fetch_assoc();
		return "./uploads/".$row{'name'};
	}
	
	function createShortDescription($string){
		$return = preg_split('/\s+/', $string);
		$return = array_slice($return, 0, 20);
		
		$newString = "";
		foreach($return as $r){
			$newString .= $r." ";
		}
		return $newString;
	}
}

?>