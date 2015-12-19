<?php
$title = "Profile";
include("models/user.class.php");
require_once("utils/utils.php");
require_once("utils/support_functions.php");

include("site_body.php");

$stmt = $mysql->prepare("SELECT * from users WHERE user_id = ?");
$stmt->bind_param("i",$_GET['user']);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0)
{
	echo "<div class='alert alert-warning'>User does not exist.</div>";
	include("site_footer.php");
	exit();
}


if(isset($_POST['set_user_type']))
{
	if($siteUser->getType() == "Admin")
	{
		$user_type = "";
		if($_POST['user_type'] == "Reader")
			$user_type = "Reader";
		else if($_POST['user_type'] == "Author")
			$user_type = "Author";
		else if($_POST['user_type'] == "Admin")
			$user_type = "Admin";

		if($user_type != "")
		{
			$stmt = $mysql->prepare("UPDATE users SET type = ?, verified = 1 WHERE user_id = ?");
			$stmt->bind_param("si",$user_type,$_GET['user']);
			$stmt->execute();
			$result = $stmt->get_result();

			echo "<div class='alert alert-warning'>User type has been changed.</div>";
		}
	}
}


$userData = User::getUserData($_GET['user'], $mysql)[0];
$userAdventures = $adv->getUsersAdventures($_GET['user']);
?>
<div class="panel panel-default" style="margin-top: 20px;">
	<div class="panel-body">
		<div class="page-header">
			<?php
				$reader_selected = "";
				$author_selected = "";
				$admin_selected = "";

				if($userData['Type'] == "Reader")
					$reader_selected = "selected = 'selected'";
				else if($userData['Type'] == "Author")
					$author_selected = "selected = 'selected'";
				else if($userData['Type'] == "Admin")
					$admin_selected = "selected = 'selected'";

				if ($siteUser->getType() == "Admin")
				{
					echo "<form action='http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]' method='POST' class='form-inline'>";
					echo "<label for='user_type'>Type: </label>
					<select class='form-control' id='user_type' name='user_type' style='width: 100px; margin-left: 10px;'>
    					<option value='Reader' ".$reader_selected.">Reader</option>
    					<option value='Author' ".$author_selected.">Author</option>
    					<option value='Admin' ".$admin_selected.">Admin</option>
 					 </select>";

					echo "<button type='submit' class='btn btn-default' name='set_user_type' style='margin-left: 10px;'>Set";
					if ($userData['Verified'] === '0') echo " and Verify";
					echo "</button></form>";
				}

			?>

			<h3><?php echo $userData['Username'];			 
				if($userData['Verified'] === '1'){
					echo "<small><span class='glyphicon glyphicon-ok-circle' style='color: blue;' title='Verified'> </span>";
				} 
				$datetime = new DateTime($userData['RegistrationDate']);
				?>
			
			<span style='float:right;'><?php echo "Registered: {$datetime->format('Y-m-d')}"; ?></span></small></h3>
		</div>
		<blockquote>
			<p><?php echo $userData['Name']; ?></p>
			<footer class='text-capitalize'><?php echo "{$userData['Type']} from {$a->getCountryNameById($userData['Country'])}"; ?></footer>
		</blockquote>
		<hr />
		<?php if(!empty($userAdventures)){  ?>
		<h3>Contributions</h3>
			<div class='row'>
			  <?php foreach($userAdventures as $hisadv){
				  echo "
						
			  <div class='col-sm-6 col-md-4'>
				<div class='thumbnail'>
				  <img src='{$hisadv["MainPicture"]}' alt='...'>
				  <div class='caption'>
					<h3>{$hisadv['Title']}</h3>
					<p>{$hisadv['ShortDescription']}...</p>
					<p><a href='adventure.php?id={$hisadv['ID']}'>View Adventure</a></p>
				  </div>
				</div>		  
			</div>";
		  }
			  echo "</div>";
		} ?>

	</div>
</div>


<?php
require_once("site_footer.php");
?>