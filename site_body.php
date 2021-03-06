<?php
if(count(get_included_files()) ==1) exit("Access denied");

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

<html lang="en" style="margin: 0; padding: 0; height:100%;">
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
    <script src="js/jquery.isloading.min.js"></script>
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

<body style="margin: 0; padding: 0; height:100%;">
<div id="login" class="container-fluid login" style='z-index: 999'>

    <div class="row">
	<div class="col-md-6 col-md-offset-3">

            <?php if($isLoggedIn){
                ?>
                <!-- List User Options Here -->
				<div class="well well-sm" style='margin-top: 10px; background-color: inherit;'>
				<h4 style='color: white;'>Panel</h4>
				<div class="list-group" style='background-color: inherit; background-color: inherit;'>
				  <a href="profile.php?user=<?php echo $userObject->getUserId(); ?>" class="list-group-item" style='background-color: inherit; color: white;'>Your Profile</a>
				  <a href="panel/usercp.php" class="list-group-item" style='background-color: inherit; color: white;'>Edit Profile</a>
				  <?php
                    if($siteUser->getType() == "Admin")
                    {
                        echo "<a href='panel/usercp.php?tab=admin' class='list-group-item' style='background-color: inherit; color: white;'>Admin Panel</a>";
                    }
					if($siteUser->getType() == "Admin" or $siteUser->getType() == "Author"){
						 echo "<a href='new_adventure.php' class='list-group-item' style='background-color: inherit; color: white;'>New Adventure</a>";
					}

                  ?>
				  
				  <a href="utils/requests.php?a=logout" class="list-group-item" style='background-color: inherit; color: white;'>Log Out</a>
				</div>
				</div>
                    				
            <?php }else{ ?>
			
                
                <div style="margin-top: 5px;">
                    <form class="form-horizontal" action="utils/requests.php" method="POST">
                        <h4 style="color: white;">Enter your login Credentials:</h4>
                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label" style="color: white;">Username</label>
                            <div class="col-sm-10">
                                <input type="text" id="username" name="username" class="form-control" placeholder="Username">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label" style="color: white;">Password</label>
                            <div class="col-sm-10">
                                <input type="password" id="password" name="password" class="form-control" placeholder="Password">
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
</div>

<div class="nav_bottom">
    <div class="nav_items">
        <ul class="nav_board">
            <li class="nav_board"><a href="index.php" class="nav_board">Home</a></li>
            <li class="nav_board"><a href="alladventures.php?&sort=newest" class="nav_board">Latest</a></li>
			<li class="nav_board"><a href="users.php" class="nav_board">All Users</a></li>
			<li class="nav_board"><a href="authors.php" class="nav_board">Authors</a></li>
            <li class="nav_board"><a href="search.php" class="nav_board">Search Adventures</a></li>
			<li class="nav_board" style='float:right; font-size:20px; cursor:pointer;'><span id='search' class='glyphicon glyphicon-search'></span>
			<div id='navSearchBox' style='float: right; visibility:hidden;'>
				<form action="search.php" method="POST">
					<input type="text" name='title' class="form-control nav_board" placeholder="Search for Adventure" style='display: table-cell; vertical-align: middle; overflow: hidden;'>
					<input type='submit' name='search' style='visibility: hidden;'>
                    <input type="hidden" name="unrated_adventures" value="true"/>
				</form>
			</div>
        </ul>
    </div>
</div>

<div id="wrapper" style="position: relative; min-height: 100%; padding-top: 10px;">
<div class="container" style="padding-bottom: 150px;">
    <div class="row">
        <div class="col-md-12">
            
                                
