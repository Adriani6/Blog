<?php

require_once 'user.class.php';
require_once 'country.class.php';
require_once 'adventure.class.php';

class Requests{
	
	private $token = "";
		
	function requestDataAccess(){
		
	}
	
	static function requestNewAccessToken(){}
	
	function getData($token, $data){
		if($this->verifyToken($token)){
			//Token Verified Successfully.
		}
	}
	
	function getTokenValue(){
		return $this->token;
	}
	
	function verifyToken($token){
		if($this->token === $token)
			return true;
		
		return false;
	}
}

?>