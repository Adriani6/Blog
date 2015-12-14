<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/utils/utils.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/utils/support_functions.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/site_body.php");

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



require_once("{$_SERVER['DOCUMENT_ROOT']}/site_footer.php");
?>