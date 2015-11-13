<?php
$server = "eu-cdbr-azure-north-d.cloudapp.net";
$user = "b3216f07d20ee7";
$pass = "d597404f";
$db = "blog-db";

$connection = new mysqli_connect($server, $user, $pass, $db);

if($connection->connection_error){
	die("Connection Failed.")
}
mysqli_query($connection, "
CREATE TABLE Users(
user_id int Primary Key,
username varchar(30) not null,
password varchar(30) not null,
)

");
?>