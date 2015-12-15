<?php
error_reporting(-1);

//phpinfo();
//exit();
require_once 'models/user.class.php';
require_once 'utils/handler.php';
$title = "Home";
$user = new User($mySQL);

require_once("site_body.php");

// site content
include("topadventures.php");

echo "Elo " . $mySQL::$CONN;

?>

	

<?php
require_once("site_footer.php");
?>