<?php

	require_once 'handler.php';
	require_once 'mysql.php';
	
	$handler = new Handler();
	$sql = new MySQL();
	
	#Check for form POST & Handle a login/register request.
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['login'])){
			if($handler->login($_POST['username'], $_POST['password'])){
				header("Location: ../index.php");
			}else{
				header('Location: ../login.php?err=authError');
			}
			
		}else if(isset($_POST['loginUCP'])){
			if($handler->login($_POST['username'], $_POST['password'])){
				header('Location: ../panel/usercp.php');
				$_SESSION['ucp'] = true;
			}else{
				header('Location: ../panel/login.php?err=auth');
			}
			
		}else if(isset($_POST['register'])){
					$handler->register($_POST['username'], $_POST['password'], $_POST['cpassword'], $_POST['name'], $_POST['country']);
		}
	
	}
	if($_SERVER['REQUEST_METHOD'] == 'GET'){
		#Logout Request
		if(isset($_GET['a'])){
			if($_GET['a'] === 'logout'){
				$handler->logout();
				header('Location: ../index.php');
			}
		}
		if(isset($_GET['data'])){
			if(strcmp($_GET['data'], "usercp")){
				if($_SESSION['token'] === $_GET['token']){
					$result = $sql->query("SELECT username, type, country, verified, name FROM users WHERE username = '".$_SESSION['user']."'");
					$outp = "[";
					while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
						if ($outp != "[") {$outp .= ",";}
						$outp .= '{"Username":"'  . $rs["username"] . '",';
						$outp .= '"Name":"'  . $rs["name"] . '",';
						$outp .= '"Verified":"'  . $rs["verified"] . '",';
						$outp .= '"Location":"'  . $rs["country"] . '",';
						$outp .= '"Type":"'   . $rs["type"]        . '"}';
					}
					$outp .="]";
					
					echo($outp);	
				}else{
					echo "Unauthorized Access: Invalid Token.";
				}
			}else if(strcmp($_GET['data'], "ucpupdate")){
				$sql->query("UPDATE users SET username = '".$_GET['change']."' WHERE username = '".$_GET['username']."' ");
				$_SESSION['user'] = $_GET['username'];				
				echo "Update Ran";
			}else{
				echo "Invalid data request.";
			}

		}		
	
	}
	
	

	
?>