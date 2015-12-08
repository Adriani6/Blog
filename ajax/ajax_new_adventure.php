<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/utils/utils.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/utils/support_functions.php");

$result = '';
$countryId = "";


if(strlen($_POST['title']) < 5)
    $result .= "Title must be at least 5 characters long.<br>";
else if(strlen($_POST['title']) > 50)
    $result .= "Title can be maximum 50 characters long.<br>";

if(($countryId = getCountryID($_POST['country'],$mysql)) == null) {
    $result = "An error occurred.";
    exit();
}


if(strlen($_POST['description']) < 5)
    $result .= "Description must be at least 10 characters long.<br>";
else if(strlen($_POST['description']) > 1000)
    $result .= "Description can be maximum 1000 characters long.<br>";


$rearrayedPictureFILES = rearrayFiles($_FILES['picture']);
$mainPicture = $_FILES['main_picture'];

if ($mainPicture['name'] != "") {
    $output = validateUploadedImageFile($mainPicture);
    if ($output != 1)
        $result .= "{$mainPicture['name']} - {$output}";
}
else {
    $result .= "You need to select main picture.<br>";
}


// warning: remember to use count -1
// last entry in the array will always be empty because there's always
// one extra file input to add new picture
for ($i = 0; $i < count($rearrayedPictureFILES) - 1; $i++) {
    $output = validateUploadedImageFile($rearrayedPictureFILES[$i]);

    if ($output != 1)
        $result .= "{$rearrayedPictureFILES[$i]['name']} - {$output}";
}


foreach ($_POST['tag'] as $tag) {
    $allowed = array("-");
    if (!ctype_alnum(str_replace($allowed, '', $tag ))) {
        $result .= "<{$tag}> tag has invalid value. Tags can only contain characters, digits and the \"-\" symbol.";
        continue;
    }

    if(strlen($tag) < 3)
        $result .= "<{$tag}> tag is too short. Tags must be at least 3 characters long.";
    else if(strlen($tag) > 25)
        $result .= "<{$tag}> tag is too long. Tags can be maximum 25 characters long.";
}


if ($result != "")
{
    echo $result;
    return;
}


/***** All form input is valid.
 ***** Attempt to add the adventure.
 *****/

$adventure_id = -1;
$mainPicture["unique_name"] = generateUniqueImageName($mainPicture["name"],"test",$mysql);
if (move_uploaded_file($mainPicture["tmp_name"], "{$_SERVER['DOCUMENT_ROOT']}/uploads/".$mainPicture["unique_name"]))
{
    $r = $mysql->query("INSERT INTO adventure(title,country_id,description)
                   VALUES ('".$_POST['title']."','".$countryId."','".$_POST['description']."')");
    $adventure_id = $mysql->getMysqli()->insert_id;

    $r = $mysql->query("INSERT INTO picture(adventure_id,name)
                   VALUES ('".$mysql->getMysqli()->insert_id."','".$mainPicture["unique_name"]."')");
    $main_picture_id = $mysql->getMysqli()->insert_id;

    $r = $mysql->query("UPDATE adventure SET main_picture_id={$main_picture_id} WHERE adventure_id={$adventure_id}");
} else {
    echo "Sorry there was an error adding your adventure.<br>";
    return;
}



for ($i = 0; $i < count($rearrayedPictureFILES) - 1; $i++) {
    $pic = $rearrayedPictureFILES[$i];
    $pic["unique_name"] = generateUniqueImageName($pic["name"],"test",$mysql);

    move_uploaded_file($pic["tmp_name"], "{$_SERVER['DOCUMENT_ROOT']}/uploads/".$pic["unique_name"]);

    $r = $mysql->query("INSERT INTO picture(adventure_id,name)
                   VALUES ('".$adventure_id."','".$pic["unique_name"]."')");
}


foreach ($_POST['tag'] as $tag) {
    $r = $mysql->query("INSERT INTO tags(adventure_id,value)
                   VALUES ('".$adventure_id."','".$tag."')");
}


echo $adventure_id;
?>