<?php
	
	require_once 'handler.php';
	require_once 'mysql.php';
	require_once 'utils.php';
	require_once "/../models/user.class.php";
	
	$handler = new Handler();
	//$sql = new MySQLClass();
	$user = new User();
	//$user = new User();
	#Check for form POST & Handle a login/register request.
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['login'])){
			
			/*
			if($handler->login($_POST['username'], $_POST['password'])){
				header("Location: ../index.php");
			}else{
				header('Location: ../login.php?err=authError');
			}*/
			if($user->login($_POST['username'],$_POST['password'])){
				$_SESSION['userClass'] = $user;
				//var_dump($user);
				header("Location: ../index.php");
			}
			else {
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
					//$handler->register($_POST['username'], $_POST['password'], $_POST['cpassword'], $_POST['name'], $_POST['country']);
		}
	
	}
		#Logout Request
		if(isset($_GET['a'])){
			if($_GET['a'] === 'logout'){
				$handler->logout();
				header('Location: ../index.php');
			}
		}else
		if(isset($_GET['data'])){
			
			if($_GET['data'] === "usercp"){
				if($_SESSION['token'] === $_GET['token']){
					$result = $sql->query("SELECT username, type, country_id, verified, name FROM users WHERE username = '".$_SESSION['user']."'");
					$outp = "[";
					while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
						if ($outp != "[") {$outp .= ",";}
						$outp .= '{"Username":"'  . $rs["username"] . '",';
						$outp .= '"Name":"'  . $rs["name"] . '",';
						$outp .= '"Verified":"'  . $rs["verified"] . '",';
						$outp .= '"Location":"'  . $rs["country_id"] . '",';
						$outp .= '"Type":"'   . $rs["type"]        . '"}';
					}
					$outp .="]";
					
					echo($outp);	
				}else{
					echo "Unauthorized Access: Invalid Token.";
				}
			}
		}
			
		if(isset($_POST['val'])){
			if(strcmp($_POST['val'], "ucpupdate")){
				if(isset($_POST['change']) && $_POST['change'] === "name"){
					if(isset($_POST["data"])){
						$sql->query("UPDATE users SET name = '".$_POST['data']."' WHERE username = '".$_SESSION['user']."' ");
						$_SESSION['user'] = $_POST['change'];
						echo "Update Ran";
					}else{
						echo "New username cannot be empty.";
					}
				}elseif(isset($_POST['change']) && $_POST['change'] === "country") {
					$sql->query("UPDATE users SET country_id = '".$_POST['data']."' WHERE username = '".$_SESSION['user']."' ");				
					echo "Country Updated.";
				}else{
					echo "Invalid data change request.";
				}
			}
		}
				
	
	
	
	

	
?>