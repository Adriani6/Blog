<?php
if(count(get_included_files()) ==1) exit("Access denied");

	require_once 'mysql.php';
	require_once ('/../models/siteuser.class.php');


	$mysql = new MySQLClass();
    $siteUser = new SiteUser($mysql);
?>