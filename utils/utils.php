<?php
	require_once 'mysql.php';
	require_once ("{$_SERVER['DOCUMENT_ROOT']}/models/siteuser.class.php");
//C:\Users\Krzychu\Desktop\TravelBlog

	$mysql = new MySQLClass();
    $siteUser = new SiteUser($mysql);

?>