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
				} ?>
			
			<span style='float:right;'><?php echo "Last Seen: {$userData['LastSeen']}"; ?></span></small></h3>
		</div>
		<blockquote>
			<p><?php echo $userData['Name']; ?></p>
			<footer class='text-capitalize'><?php echo "{$userData['Type']} from {$a->getCountryNameById($userData['Country'])}"; ?></footer>
		</blockquote>
		<hr />

		  <?php foreach($userAdventures as $hisadv){
			  echo "
			  		<div class='row'>
		  <div class='col-sm-6 col-md-4'>
			<div class='thumbnail'>
			  <img src='...' alt='...'>
			  <div class='caption'>
				<h3>Thumbnail label</h3>
				<p>...</p>
				<p>View Adventure</p>
			  </div>
			</div>		  </div>
		</div>";
		  } ?>

	</div>
</div>

<?php
require_once("site_footer.php");
?>