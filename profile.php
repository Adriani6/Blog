<?php
/**
 * Created by PhpStorm.
 * User: 1204848
 * Date: 30/11/2015
 * Time: 16:27
 */

//Connection to database
include ("db_connect.php");

//user_id stored in local variable.
$ID = $_GET["ID"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<nav>
    <?//Link to homepage.?>
    <p><a href=/coursework/Index.php>Home</a></p>
    <?//Link to panel.?>
    <p><a href=/coursework/Panel.php>Edit Profile</a></p>
    <?

    //Getting name of user from database based on userID.
    $sql_query_name = "SELECT name FROM Users where user_id = $ID";
    $result = $db->query($sql_query_name);
    while($row = $result->fetch_array()){
        echo "<p>Name: ".$row['name'];
    }

    //Getting user's country from database based on userID.
    $sql_query_country = "SELECT country FROM Users where user_id = $ID";
    $result = $db->query($sql_query_country);
    while($row = $result->fetch_array()){
        echo "<p>Country: ".$row['country'];
    }
    ?>
    <p>Adventures:</p>
    <?
    //Creating list of adventure links using userID.
    $sql_query_adventures = "SELECT * FROM Adventure where user_ID = $ID";
    $result = $db->query($sql_query_adventures);
    while($row = $result->fetch_array()){
        echo "<li><a href=\"Adventure.php?adventureID=". $row['adventure_id']."\">".$row['title']."</a>";
    }
    ?>
</nav>

