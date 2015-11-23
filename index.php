<?php
	require_once 'utils/mysql.php'; 
	require_once 'utils/debugging.php';
	?>

<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Template Example</title>
    <meta name="description" content="The HTML5 Herald">
    <meta name="author" content="SitePoint">

    <link rel="stylesheet" href="css/board.css">
	<link rel="stylesheet"	href="bootstrap_css/bootstrap.min.css">
    <script src="js/html_utils.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/effects/effects.js"></script>
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body>
    <div class="container-fluid login">

		<div class="row">
		  
			<div class="center-block">
				<div class="col-md-4" ></div>
				<div class="col-md-4" style="margin-top: 5px;">
					<form class="form-horizontal">
					<h4 style="color: white;">Enter your login Credentials:</h4>
					  <div class="form-group">
						<label for="input" class="col-sm-2 control-label" style="color: white;">Username</label>
						<div class="col-sm-10">
						  <input type="user" class="form-control" id="input" placeholder="Username">
						</div>
					  </div>
					  <div class="form-group">
						<label for="input" class="col-sm-2 control-label" style="color: white;">Password</label>
						<div class="col-sm-10">
						  <input type="password" class="form-control" id="input" placeholder="Password">
						</div>
					  </div>
					  <div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
						  <button type="submit" class="btn btn-default">Sign in</button>
						  <button type="submit" class="btn btn-default">Register</button>
						</div>
					  </div>
					</form>
				</div>
				<div class="col-md-4" ></div>
			</div>
		  
		</div>

    </div>

    <div class="nav_top">
        <div class="nav_top_content">
		Login/Register
		</div>
    </div>
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
	
	<div style="margin-left: 100px; margin-right: 100px;">
		<div class="row" style="margin: 0 auto;">
		  <div class="col-md-8">
			<div class="panel panel-default" style="margin-top: 10px;">
				<div class="panel-heading" style="background-color: orange;">Title</div>
					<div class="panel-body">
						Adventure content
				</div>
			</div>
		</div>
		  	<!--
				Left side widget boxes
			-->
			<div class="col-md-4">
				<div class="panel panel-default" style="margin-top: 10px;">
						<div class="panel-heading" style="background-color: orange;">Widget Title</div>
							<div class="panel-body">
								Widget content
							</div>
				</div>
			</div>
				
		</div>
    </div>    


</body>
</html>