<?php
if(count(get_included_files()) ==1) exit("Access denied");

require_once ("/../utils/mysql.php");

class Country{
	private $mySQL;
	private $country_name;
	private $country_code;
	
	function __construct($mysql, $countryId = "") {
		$this->mySQL = $mysql;
		if(!empty($countryId)){
			$this->createCountryDetails($countryId);
		}
	}
	
	function getCountryNameById($countryId, $mysql=""){
		if(!empty($mysql)){$this->mySQL = $mysql;}
		$id = intval($countryId);
		$result = $this->mySQL->query("SELECT name FROM country WHERE id = {$id}");
		if($result->num_rows == 0) {
			return null;
		}

		$row = $result->fetch_assoc();
		return $row['name'];
	}
	
	function getCountryCodeById($countryId){
		$id = intval($countryId);
		$result = $this->mySQL->query("SELECT code FROM country WHERE id = {$id}");
		if($result->num_rows == 0) {
			return null;
		}

		$row = $result->fetch_assoc();
		return $row['code'];
	}
	
	function createCountryDetails($countryId){
		$this->country_name = $this->getCountryNameById($countryId);
		$this->country_code = $this->getCountryCodeById($countryId);		
	}
	
	function getName(){
		return $this->country_name;
	}
	
	function getCountryCode(){
		return $this->country_code;
	}
		
}

?>