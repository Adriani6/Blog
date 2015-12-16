<?php
require_once("site_body.php");
require_once("utils/utils.php");
require_once("utils/handler.php");
require_once("models/adventure.class.php");

$id = intval($_GET['id']);
$adventure = $adv->getAdventure($id,$mySQL);


require_once("adventure_body.php");
?>



<?php
require_once("site_footer.php");
?>