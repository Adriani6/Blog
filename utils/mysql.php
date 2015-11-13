<?php
$server = "eu-cdbr-azure-north-d.cloudapp.net";
$user = "b3216f07d20ee7";
$pass = "d597404f";
$db = "blog-db";

$connection = new mysqli($server, $user, $pass);

if($connection->connection_error){
	die("Connection Failed.")
}

?>