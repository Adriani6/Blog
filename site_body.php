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
    $isLoggedIn = $userObject->isLoggedIn();
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
    <meta property="og:description"   content="View adventures from around the world." />
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
    <meta name="description" content="Share Adventures from around the world.">
    <meta name="author" content="Here for Beer">

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

<div class="container-fluid login" style='z-index: 999'>

    <div class="row">
	<div class="col-md-6 col-md-offset-3">

            <?php if($isLoggedIn){
                ?>
                <!-- List User Options Here -->
				<div class="well well-sm" style='margin-top: 10px;'>
				<h4>Panel</h4>
				<div class="list-group">
				  <a href="panel/usercp.php" class="list-group-item">Edit Profile</a>
				  <?php
                    if($siteUser->getType() == "Admin")
                    {
                        echo "<a href='panel/usercp.php?tab=admin' class='list-group-item'>Admin Panel</a>";
                    }
					if($siteUser->getType() == "Admin" or $siteUser->getType() == "Author"){
						 echo "<a href='new_adventure.php' class='list-group-item'>New Adventure</a>";
					}

                  ?>
				  
				  <a href="utils/requests.php?a=logout" class="list-group-item">Log Out</a>
				</div>
				</div>
                    				
            <?php }else{ ?>
			
                
                <div style="margin-top: 5px;">
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
            <li class="nav_board"><a href="alladventures.php?&sort=newest" class="nav_board">Latest</a></li>
			 <li class="nav_board"><a href="authors.php" class="nav_board">Authors</a></li>
            <li class="nav_board"><a href="search.php" class="nav_board">Search</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            
                                
