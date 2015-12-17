<?php
	
	require_once 'handler.php';
	require_once 'mysql.php';
	require_once 'utils.php';
	require_once "/../models/user.class.php";
	
	$handler = new Handler($mysql);
	//$sql = new MySQLClass();
	$user = new User($mysql);
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
			if($siteUser->login($_POST['username'],$_POST['password'])){
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
				$siteUser->logOut();
				header('Location: ../index.php');
			}
		}else
		if(isset($_GET['data'])){
			
			if($_GET['data'] === "usercp"){
				if($siteUser->isLoggedIn() == true)
                {
					$result = $mysql->query("SELECT username, type, country_id, verified, name FROM users WHERE username = '".$siteUser->getUsername()."'");
					$outp = "[";
					while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
						if ($outp != "[") {$outp .= ",";}
						$outp .= '{"Username":"'  . $rs["username"] . '",';
						$outp .= '"Name":"'  . $rs["name"] . '",';
						$outp .= '"Verified":"'  . $rs["verified"] . '",';
						$outp .= '"Country":"'. getCountryName($rs["country_id"],$mysql) . '",';
						$outp .= '"Type":"'   . $rs["type"]        . '"}';
					}
					$outp .="]";
					
					echo($outp);
                }
			}
		}
			
		if(isset($_POST['val'])){
			if($siteUser->isLoggedIn() == false)
				return;

			if($_POST['val'] == "cpupdate"){
				if(isset($_POST['change']) && $_POST['change'] === "name"){
					if(isset($_POST["data"]))
					{
						$name_check = validateName($_POST['data']);
						if($name_check === true)
						{
							$stmt = $mysql->prepare("UPDATE users SET name = ? WHERE user_id = {$siteUser->getUserId()}  ");
							$stmt->bind_param("s",$_POST['data']);
							$stmt->execute();

							echo "Your name has been updated.";
						}
						else
							echo $name_check;
					}
				}elseif(isset($_POST['change']) && $_POST['change'] === "country") {
                    $id = getCountryID($_POST['data'],$mysql);
					if($id != null)
					{
						$mysql->query("UPDATE users SET country_id = '".$id."' WHERE user_id = {$siteUser->getUserId()} ");
						echo "Country Updated.";
					}
					else
						echo "Invalid country";

				}else{
					echo "Invalid data change request.";
				}
			}
		}
				
	
	
	
	

	
?>