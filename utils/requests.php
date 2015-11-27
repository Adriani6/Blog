<?php

	require_once 'handler.php';
	require_once 'mysql.php';
	
	$handler = new Handler();
	$sql = new MySQL();
	
	#Check for form POST & Handle a login/register request.
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['login'])){
			$handler->login($_POST['username'], $_POST['password']);
			header("Location: ../index.php");
		}else if(isset($_POST['loginUCP'])){
			if($handler->login($_POST['username'], $_POST['password'])){
				header('Location: ../panel/usercp.php');
				$_SESSION['ucp'] = true;
			}
			
		}else if(isset($_POST['register'])){
			$handler->register($_POST['username'], $_POST['password']);
		}
	
	}
	
	#Logout Request
	if(isset($_GET['a'])){
		if($_GET['a'] === 'logout'){
			$handler->logout();
			header('Location: ../index.php');
		}
	}
	
	#Data Request Handle
	if(isset($_GET['data'])){
		//if($_SESSION('Token') === $_GET['token']){
			if($_GET['data'] === "usercp"){
				
				$result = $sql->query("SELECT login, type FROM users WHERE login = 'Adriani6'");
				$outp = "[";
				while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
					if ($outp != "[") {$outp .= ",";}
					$outp .= '{"Name":"'  . $rs["login"] . '",';
					$outp .= '"Type":"'   . $rs["type"]        . '"}';
				}
				$outp .="]";
				
				echo($outp);
				//}
		}else{
					echo "You're not permitted to request Data.";
		}
	}

?>