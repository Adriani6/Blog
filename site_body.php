<?php
require_once 'utils/handler.php';
require_once 'utils/requests.php';
require_once 'models/user.class.php';
$isLoggedIn = false;
$userObject;
$a = new Country($mysql);
$adv = new Adventure($mysql);

if(isset($_SESSION['userClass'])){
	$userObject = $_SESSION['userClass'];
	$isLoggedIn = $userObject->isLoggedin();
}
function __autoload($class){
	$file = "models/".$class.".class.php";
	require_once($file);
}
?>

<!doctype html>

<html lang="en">
<head>
	
    <meta charset="utf-8">

    <meta property="og:url"           content="http://www.balala.html" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="Blog-dev" />
    <meta property="og:description"   content="Testing facebook share button" />
    <meta property="og:image"         content="imgs/wonderblog.png" />

    <title>
        <?php
        $title_suffix = "blog-dev - Adventures from all around the world";
        if(empty($title))
            echo $title_suffix;
        else
            echo $title." - ".$title_suffix;
        ?>
    </title>
    <meta name="description" content="The HTML5 Herald">
    <meta name="author" content="SitePoint">

	<script src="js/jquery.js"></script>
    <link rel="stylesheet" href="css/board.css">
    <link rel="stylesheet" href="bootstrap_css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap_css/bootstrap.min.css">
    <link rel="stylesheet" href="css/adventure_body.css">
    <link rel="stylesheet" href="css/profile.css">

	

	<script>
	//This sets the toolbar to stay on the page while scrolling, there's a bug with positioning...
	/*<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	$(document).ready(function(){
	    var $sidebar   = $("#subNav"),
        $window    = $(window),
        offset     = $sidebar.offset(),
        topPadding = 15;

		$window.scroll(function() {
			if ($window.scrollTop() > offset.top) {
				$sidebar.addClass('fixed');
			} else {
				$sidebar.removeClass('fixed');
			}
		});
	});
*/

</script>

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body>

<div class="container-fluid login">

    <div class="row">
        <div class="center-block">
            <?php if($isLoggedIn){
                ?>
                <!-- List User Options Here -->
                <div class="col-md-4" style="color:white;"><h4>User Panel</h4><br />
                    <a href="panel/login.php">User CP</a><br />
                    <?php if(isset($_SESSION['userClass'])){
							if($userObject->getAccountType($mysql) === "ADMIN"){
                        ?>
                        <a href="panel/admincp.php">Admin CP</a><br />
                    <?php } } ?>
                    <a href="utils/requests.php?a=logout">Log Out</a>
                </div>

            <?php }else{ ?>
                <div class="col-md-4" ></div>
                <div class="col-md-4" style="margin-top: 5px;">
                    <form class="form-horizontal" action="utils/requests.php" method="POST">
                        <h4 style="color: white;">Enter your login Credentials:</h4>
                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label" style="color: white;">Username</label>
                            <div class="col-sm-10">
                                <input type="username" name="username" class="form-control" id="input" placeholder="Username">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label" style="color: white;">Password</label>
                            <div class="col-sm-10">
                                <input type="password" name="password" class="form-control" id="input" placeholder="Password">
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
                <div class="col-md-4" ></div>
            <?php }?>
        </div>
    </div>

</div>

<div class="nav_top">
    <div class="nav_top_content">
        <?php if($isLoggedIn){
            echo "<div class='glyphicon glyphicon-user' style='display: inline-block; line-height: normal; vertical-align: middle;'>{$userObject->getUsername()}</div>";
            ?>
        <?php }else{ ?>
            Login/Register
        <?php } ?>
    </div>
</div>
<div class="nav_mid">
    <img class="logo" src="imgs/wonderblog.png" alt="Logo">
    <a href="index.php">
</div>

<div class="nav_bottom">
    <div class="nav_items">
        <ul class="nav_board">
            <li class="nav_board"><a href="index.php" class="nav_board">Home</a></li>
			<li class="nav_board"><a href="alladventures.php" class="nav_board">Adventures</a></li>
            <li class="nav_board"><a href="login_test.php" class="nav_board">Log in</a></li>
            <li class="nav_board"><a href="logout_test.php" class="nav_board">Log out</a></li>
            <li class="nav_board"><a href="new_adventure.php" class="nav_board">New Adventure</a></li>
            <li class="nav_board"><a href="adventure.php?id=" class="nav_board">Show Adventure</a></li>
            <li class="nav_board"><a href="search.php" class="nav_board">Search</a></li>
			<li class="nav_board"><span id="calendar" class="glyphicon glyphicon-calendar" style="font-size:20px; color: white;"></span></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            
                                
