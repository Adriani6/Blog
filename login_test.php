<?php
require_once("utils/utils.php");
require_once("utils/support_functions.php");

require_once("site_body.php");
?>

    Change link to login_test.php?username=[username]&password=[password]<br>
    For example login_test.php?username=bobmac&password=aidan1234<br><br>

    Login check:<br>
<?php
if(isset($_GET['username'],$_GET['password']))
{
    echo "username: ".$_GET['username']."<br/>";
    echo "password: ".$_GET['password']."<br/>";

    if($siteUser->logIn($_GET['username'],$_GET['password']) == true)
        echo "Details correct. Logged in.";
    else
        echo "Details incorrect.";
}
else
{
    echo "username or password not set";
}
?>


<?php
require_once("site_footer.php");
?>