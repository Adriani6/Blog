<?php

require_once("utils/support_functions.php");
require_once("site_body.php");

$adventure = null;

if(isset($_GET['id']))
    $adventure = getAdventure($_GET['id'],$mysql);

if($adventure != null)
    require_once("adventure_body.php");
else
    echo "<div class='alert alert-warning'>The adventure you are looking for does not exist or has been deleted.</div>";
?>



<?php
require_once("site_footer.php");
?>