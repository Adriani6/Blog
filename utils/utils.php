<?php

	require_once 'mysql.php';
	
	function querySQL($query){
		$result = mysqli_query($connection, $query);
		
		return $result;
		
	}

?>