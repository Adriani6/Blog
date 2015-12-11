<?php 

require_once 'country.class.php';
require_once 'user.class.php';
require_once 'adventure.class.php';
//require_once '../utils/handler.php';

$c = new Country(11);
$u = new User("Adriani6");

//$c->hi();


$obj = $u->findAdventure("1");

echo "Title: " . $obj->getTitle();

//print_r($array);
/*
$array = $a->getAllAdventuresAsArray(4);
$array2 = $a->getAdventureIds(4);

print_r($array2);
echo $u->login("Adriani5", "adrian");
echo $u->getUsername();
echo $u->getCountry()->getCountryName();
echo $u->getCountry()->getCountryCode();
*/
?>