<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Template Example</title>
    <meta name="description" content="The HTML5 Herald">
    <meta name="author" content="SitePoint">

    <link rel="stylesheet" href="css/board.css">
    <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
    <script src="js/html_utils.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/effects/effects.js"></script>
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body>
    <div class="login">
        <form method="post" class="login_form" id="field" action="register/register.php">
            <p><input type="text" name="username" placeholder="Username" id="field"></p>
            <p><input type="password" name="password" placeholder="Password" id="field"></p>

            <input type="submit" value="Login" class="login_button"> <input type="submit" value="Register" class="login_button">
        </form>

    </div>
    <div class="nav_top">
        <div class="nav_top_content">
</div>
    </div>
    <div class="nav_mid"></div>
    <div class="nav_bottom">
        <div class="nav_items">
            <ul class="nav">
                <li class="nav"><a href="#" class="nav">Home</a></li>
                <li class="nav"><a href="#" class="nav">Adventures</a></li>
            </ul>
        </div>
    </div>
    <div class="container">
        <div class="body_wrapper shadow">
            <div class="head">Adventure #1</div>
        </div>
        <div class="widget shadow">
            <div class="head">Calendar</div>
        </div>

        <div class="widget shadow">
            <div class="head">Widget #2</div>
        </div>
    </div>

</body>
</html>