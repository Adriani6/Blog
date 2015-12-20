<?php
require_once 'utils/handler.php';
require_once 'utils/requests.php';

if(isset($_SESSION['userClass'])){
	header("Location: index.php");
}

require_once("site_body.php");
?>

<?php if(isset($_GET['err'])){
	if($_GET['err'] === "authError"){
		?>
		<div class="alert alert-danger" role="alert" style="">Incorrect Login Credentials, try again...</div>
		<?php
	}
} ?>

	<div class="panel panel-default">
		<div class="panel-body">
			<h4>Login</h4>
			<hr />
			<form method="POST" action="utils/requests.php" class="form-horizontal">
				<div class="form-group">
					<label for="username" class="col-sm-2 control-label">Username</label>
					<div class="col-sm-10">
						<input type="username" class="form-control" id="username" name="username" placeholder="Username">
					</div>
				</div>
				<div class="form-group">
					<label for="password" class="col-sm-2 control-label">Password</label>
					<div class="col-sm-10">
						<input type="password" class="form-control" id="password" name="password" placeholder="Password">
					</div>
				</div>
				<!--
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label>
                            <input type="checkbox"> Remember me (1 Day)
                            </label>
                        </div>
                    </div>
                </div>
                -->
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" name="login" class="btn btn-default">Sign in</button>
						<a href="register.php" style="float: right;">Don't have an account? Register!</a>
					</div>
				</div>
			</form>
		</div>
	</div>


<?php
require_once("site_footer.php");
?>