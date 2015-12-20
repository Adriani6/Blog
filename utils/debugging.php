<?php
if(count(get_included_files()) ==1) exit("Access denied");

ini_setini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
?>