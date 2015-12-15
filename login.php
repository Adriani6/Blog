<?php
require_once 'utils/handler.php';
require_once 'utils/requests.php';

if(isset($_SESSION['userClass'])){
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
    <script src="js/html_utils.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/effects/effects.js"></script>
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
						<h4>Login</h4>
						<hr />
						<form method="POST" action="utils/requests.php" class="form-horizontal">
							<div class="form-group">
								<label for="username" class="col-sm-2 control-label">Username</label>
								<div class="col-sm-10">
									<input type="username" class="form-control" id="username" placeholder="Username">
								</div>
							</div>
							<div class="form-group">
								<label for="password" class="col-sm-2 control-label">Password</label>
								<div class="col-sm-10">
									<input type="password" class="form-control" id="password" placeholder="Password">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<div class="checkbox">
										<label>
										<input type="checkbox"> Remember me (1 Day)
										</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" name="login" class="btn btn-default">Sign in</button>
									<a href="register.php" style="float: right;">Don't have an account? Register!</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>


</body>
</html>