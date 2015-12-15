<?php
	require_once 'mysql.php';
	require_once ('/../models/siteuser.class.php');


	$mysql = new MySQLClass();
    $siteUser = new SiteUser($mysql);
?>