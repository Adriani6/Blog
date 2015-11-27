<?php
class MySQL{
	protected $server = "eu-cdbr-azure-north-d.cloudapp.net";
	protected $user = "b3216f07d20ee7";
	protected $pass = "d597404f";
	protected $db = "blog-db";
	protected $port = 3306;

	function query($query){
		$connection = mysqli_connect($this->server, $this->user, $this->pass, $this->db, $this->port);
		if(mysqli_connect_errno()){
			echo "Connection Failed.";
		}
				
		$result = mysqli_query($connection, $query);
		if(!$result){
			die('Could not query:' . mysql_error());
		}
				
		return $result;
			
	}
	
}
?>