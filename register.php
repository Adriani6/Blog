<?php
$title = "Register";
require_once("site_body.php");
?>

<h4>Register</h4>
<hr />

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($handler->getRegistrationResult() != '') {
		echo '<div class="alert alert-danger">'.$handler->getRegistrationResult().'</div>';
	}
}
?>

<form class="form-horizontal" action="register.php" method="POST" id="form">
	<div class="form-group">
		<label for="username" class="col-sm-2 control-label">Username</label>
		<div class="col-sm-10">
			<input type="text" name="username" class="form-control" id="username" placeholder="Username"
				   value="<?php if(isset($_POST['username'])) echo  $_POST['username']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="password" class="col-sm-2 control-label">Password</label>
		<div class="col-sm-10">
			<input type="password" name="password" class="form-control" id="password" placeholder="Password">
		</div>
	</div>
	<div class="form-group">
		<label for="cpassword" class="col-sm-2 control-label">Confirm Password</label>
		<div class="col-sm-10">
			<input type="password" name="cpassword" class="form-control" id="cpassword" placeholder="Password Confirmation" onClick="verifyPasswords()">
		</div>
	</div>
		<div id="alert"> </div>
		<hr />
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10">
			<input type="text" name="name" class="form-control" id="name" placeholder="Your name"
				   value="<?php if(isset($_POST['name'])) echo  $_POST['name']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="country" class="col-sm-2 control-label">Country</label>
		<div class="col-sm-10">
		<?php
			renderCountrySelectControl($mySQL);
		?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default" name="register" id="register_button">Register</button>
		</div>
	</div>
</form>


<?php
require_once("site_footer.php");
?>
