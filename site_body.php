<?php
require_once 'utils/handler.php';
require_once 'utils/requests.php';

$isLoggedIn = false;
if(isset($_SESSION['user'])){
    $isLoggedIn = true;
    $handler->checkCookie($_SESSION['user']);
}
?>

<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

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

    <link rel="stylesheet" href="css/board.css">
    <link rel="stylesheet" href="bootstrap_css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <script src="js/jquery.js"></script>
    <script src="bootstrap_js/bootstrap.min.js"
            integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
            crossorigin="anonymous"></script>
    <script src="js/html_utils.js"></script>

    <script src="js/effects/effects.js"></script>
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
                    <?php if(isset($_SESSION['type']) && $_SESSION['type'] === "ADMIN"){
                        ?>
                        <a href="panel/admincp.php">Admin CP</a><br />
                    <?php } ?>
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
            echo $_SESSION['user'];
            ?>
        <?php }else{ ?>
            Login/Register
        <?php } ?>
    </div>
</div>
<div class="nav_mid"></div>
<div class="nav_bottom">
    <div class="nav_items">
        <ul class="nav_board">
            <li class="nav_board"><a href="index.php" class="nav_board">Home</a></li>
            <li class="nav_board"><a href="adventures.php" class="nav_board">Adventures</a></li>
            <li class="nav_board"><a href="new_adventure.php" class="nav_board">New Adventure</a></li>
            <li class="nav_board"><a href="adventure.php?id=" class="nav_board">Show Adventure</a></li>
            <li class="nav_board"><a href="search.php" class="nav_board">Search</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="margin-top: 10px;">
                <div class="panel-heading" style="background-color: orange;">Title</div>
                <div class="panel-body">