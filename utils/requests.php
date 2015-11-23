<?php
	
	require_once 'handler.php';
	
	$handler = new Handler();
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['login'])){
			$handler->login($_POST['username'], $_POST['password']);
		}else if(isset($_POST['register'])){
			$handler->register($_POST['username'], $_POST['password']);
		}
	
	}
	
	if(isset($_GET['a'])){
		if($_GET['a'] === 'logout'){
			$handler->logout();
			header('Location: ../index.php');
		}
	}

?>