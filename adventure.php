<?php

require_once("utils/support_functions.php");
require_once("site_body.php");


$id = intval($_GET['id']);
$adventure = getAdventure($id,$mysql);


require_once("adventure_body.php");
?>



<?php
require_once("site_footer.php");
?>