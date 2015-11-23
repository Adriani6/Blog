<?php
class MySQL{
	protected $server = "localhost";
	protected $user = "root";
	protected $pass = "";
	protected $db = "blog";
	protected $port = 3306;

	function query($query){
		$connection = mysqli_connect($this->server, $this->user, $this->pass, $this->db, $this->port);
		if(mysqli_connect_errno()){
			echo "Connection Failed.";
		}
				
		$result = mysqli_query($connection, $query);
				
		return $result;
			
	}
	
}
?>