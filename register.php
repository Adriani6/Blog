<?php

?>
<?php
require_once 'utils/handler.php';
require_once 'utils/requests.php';

if(isset($_SESSION['user'])){
	$handler->checkCookie($_SESSION['user']);
	header("Location: index.php");
}
?>
<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Adventures</title>
    <meta name="description" content="The HTML5 Herald">
    <meta name="author" content="SitePoint">

    <link rel="stylesheet" href="css/board.css">
	<link rel="stylesheet"	href="bootstrap_css/bootstrap.min.css">
	<script src="js/jquery.js"> </script>


    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body>

    <div class="nav_mid"></div>
    <div class="nav_bottom">
        <div class="nav_items">
            <ul class="nav_board">
                <li class="nav_board"><a href="index.php" class="nav_board">Home</a></li>
                <li class="nav_board"><a href="adventures.php" class="nav_board">Adventures</a></li>
				<li class="nav_board"><a href="search.php" class="nav_board">Search</a></li>
				<li class="nav_board"><a href="search.php" class="nav_board">Search</a></li>
            </ul>
        </div>
    </div>
	
	<div class="container-fluid" style="margin-top: 6%;">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<?php if(isset($_GET['err'])){
					if($_GET['err'] === "authError"){
				?>
				<div class="alert alert-danger" role="alert" style="">Incorrect Login Credentials, try again...</div>
				<?php
					}
				} ?>
				<div class="panel panel-default">
					<div class="panel-body">
						<h4>Register</h4>
						<hr />
						<form class="form-horizontal" action="utils/requests.php" method="POST" id="form">
							<div class="form-group">
								<label for="username" class="col-sm-2 control-label">Username</label>
								<div class="col-sm-10">
									<input type="text" name="username" class="form-control" id="username" placeholder="Username">
								</div>
							</div>
							<div class="form-group">
								<label for="password" class="col-sm-2 control-label">Password</label>
								<div class="col-sm-10">
									<input type="password" name="password" class="form-control" id="password" placeholder="Password">
								</div>
							</div>
							<div class="form-group">
								<label for="cpassword" class="col-sm-2 control-label">Confirm Pass.</label>
								<div class="col-sm-10">
									<input type="password" class="form-control" id="cpassword" placeholder="Password Confirmation" onClick="verifyPasswords()">
								</div>
							</div>
							<div>
							<div id="alert"> </div>
							<hr />
							<div class="form-group">
								<label for="name" class="col-sm-2 control-label">Name</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="name" placeholder="Your name">
								</div>
							</div>
							<div class="form-group">
								<label for="loc" class="col-sm-2 control-label">Location</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="loc" placeholder="Your location">
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" class="btn btn-default" name="register" id="register_button" disabled="disabled">Register</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

    <script src="js/form_verification.js"></script>
</body>
</html>