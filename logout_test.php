<?php
require_once("utils/utils.php");
require_once("utils/support_functions.php");
require_once("site_body.php");

if($siteUser->isLoggedIn() == false)
{
    echo "You were not logged in";
}
else
{
    $user = $siteUser->getUsername();
    $siteUser->logOut();
    echo "logged out ".$user;
}



require_once("site_footer.php");
?>