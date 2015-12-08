<?php 

require_once 'country.class.php';
require_once 'user.class.php';
require_once 'adventures.class.php';
//require_once '../utils/handler.php';

$c = new Country(11);
$u = new User();
$a = new Adventures();

//$c->hi();

echo $a->getAllAdventuresAsArray();
echo $u->login("Adriani5", "adrian");
echo $u->getUsername();
echo $u->getCountry()->getCountryName();
echo $u->getCountry()->getCountryCode();

?>