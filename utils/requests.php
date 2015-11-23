<?php
	
	require_once 'handler.php';
	
	$handler = new Handler();
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['login'])){
			$handler->login($_POST[''], $_POST['']);
		}else if(isset($_POST['register'])){
			$handler->register($_POST['username'], $_POST['password']);
		}
	
	}

?>