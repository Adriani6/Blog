<?php

require_once("utils/utils.php");
//require_once("utils/support_functions.php");
require_once("models/adventure.class.php");
$adventureObject = new Adventure();


require_once("utils/support_functions.php");
require_once("site_body.php");


$id = intval($_GET['id']);
$adventure = $adventureObject->getAdventure($id,$mysql);


require_once("adventure_body.php");
?>



<?php
require_once("site_footer.php");
?>