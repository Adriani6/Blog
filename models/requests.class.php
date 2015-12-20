<?php
if(count(get_included_files()) ==1) exit("Access denied");

require_once 'user.class.php';
require_once 'country.class.php';
require_once 'adventure.class.php';

class Requests{
	
	function __construct(){
		// Request | Token | 
		
		if($this->verifyToken(func_get_arg(2))){
			if(fun_get_arg(1) === "register"){
				if(!func_num_args > 3){
					die("Invalid request.");
				}
				User->register(func_get_arg(3), func_get_arg(4), isset(func_get_arg(5)), isset(func_get_arg(6)));
			}
			
		}
		//func_get_arg();
		//func_num_args();
	} 
	
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