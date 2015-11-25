<?php
	require_once '../utils/requests.php';
?>
<html>
<head>
<link rel="stylesheet" href="../bootstrap_css/bootstrap.min.css">
<link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php 
	if(isset($_GET['err']) && $_GET['err'] === "auth"){
?>
	<div class="alert alert-danger" role="alert">You need to login to access the User Panel.</div>
<?php		
	}
?>

<div class="container-fluid">
	
	<div class="row">
		<div class="col-md-6 col-md-offset-3 ">
		<div class="form_box">
			<h3>User CP - Log in</h3>
			<form class="form-inline" action="../utils/requests.php" method="POST" style="margin-top: 50px;">
			  <div class="form-group">
				<label class="sr-only" for="username">Username</label>
				<input type="username" name="username" class="form-control" id="username" placeholder="Username">
			  </div>
			  <div class="form-group">
				<label class="sr-only" for="password">Password</label>
				<input type="password" name="password" class="form-control" id="password" placeholder="Password">
			  </div>
			  <div class="form-group">
			  <button type="submit" name="loginUCP" class="btn btn-default" style="float: right;">Sign in</button>
			  </div>
			</form>
		</div>
		</div>
		<div class="wm">Adventure Blog</div>
</div>

</body>
</html>