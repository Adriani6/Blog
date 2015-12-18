<?php
$title = "Profile";
include("models/user.class.php");
require_once("utils/utils.php");
require_once("utils/support_functions.php");

include("site_body.php");


$userData = User::getUserData($_GET['user'], $mysql)[0];
$userAdventures = $adv->getUsersAdventures($_GET['user']);
?>
<div class="panel panel-default" style="margin-top: 20px;">
	<div class="panel-body">
		<div class="page-header">
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
		<?php if(!empty($hisadv)){ ?>
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
		}	?>
		</div>
	</div>
</div>

<?php
require_once("site_footer.php");
?>