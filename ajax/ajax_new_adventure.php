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
else if(!isset($_POST['edit'])){
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

if(isset($_POST['tag']))
{
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
}

if ($result != "")
{
    echo $result;
    return;
}


/***** All form input is valid.
 ***** Attempt to add or edit the adventure.
 *****/
$adventure_id = -1;

if (!isset($_POST['edit'])) // adding adventure
{
    $r = $mysql->query("INSERT INTO adventure(title,country_id,description)
               VALUES ('".$_POST['title']."','".$countryId."','".$_POST['description']."')");
    $adventure_id = $mysql->getMysqli()->insert_id;
}
else // editing adventure
{
    // TODO: check user id & adventure_id - in every query !!
    $adventure_id = $_POST['adventure_id'];
    $r = $mysql->query("UPDATE adventure
               SET title='{$_POST['title']}',country_id={$countryId},description='{$_POST['description']}'
               WHERE adventure_id = {$adventure_id}");

    // update database delete removed pictures
    $result = $mysql->query("SELECT * FROM picture WHERE adventure_id={$adventure_id}");
    while($picture = $result->fetch_assoc())
    {
        $found = false;
        foreach ($_POST['edit_picture_id'] as $edit_picture_id)
        {
            if($picture['picture_id'] == $edit_picture_id)
            {
                // picture has not been removed
                $found = true;
            }
        }

        if($found == false)
        {
            // picture has not been removed
            // unlink file and delete it from database
            unlink("{$_SERVER['DOCUMENT_ROOT']}/uploads/".$picture['name']);
            $mysql->query("DELETE FROM picture WHERE picture_id={$picture['picture_id']}");
        }
    }


    $result = $mysql->query("SELECT * FROM tags WHERE adventure_id={$adventure_id}");
    while($tag = $result->fetch_assoc())
    {
        $found = false;
        foreach ($_POST['edit_tag_id'] as $edit_tag_id)
        {
            if($tag['tag_id'] == $edit_tag_id)
            {
                // tag has not been removed
                echo "FOUND";
            }
        }

        if($found == false)
        {
            // tag has not been removed
            // delete it from database
            $mysql->query("DELETE FROM tags WHERE tag_id={$tag['tag_id']}");
        }
    }
}

if($mainPicture['name'] != "")
{
    if (isset($_POST['edit']))
    {
        $result = $mysql->query("SELECT * FROM adventure WHERE adventure_id = {$_POST['adventure_id']}");
        $adventure = $result->fetch_assoc();
        $result = $mysql->query("SELECT * FROM picture WHERE picture_id = {$adventure['main_picture_id']}");
        $main_picture = $result->fetch_assoc();

        unlink("{$_SERVER['DOCUMENT_ROOT']}/uploads/".$main_picture['name']);
        $mysql->query("DELETE FROM picture WHERE picture_id={$main_picture['picture_id']}");
    }

    $mainPicture["unique_name"] = generateUniqueImageName($mainPicture["name"],"test",$mysql);
    move_uploaded_file($mainPicture["tmp_name"], "{$_SERVER['DOCUMENT_ROOT']}/uploads/".$mainPicture["unique_name"]);
    $r = $mysql->query("INSERT INTO picture(adventure_id,name)
               VALUES ('".$mysql->getMysqli()->insert_id."','".$mainPicture["unique_name"]."')");
    $main_picture_id = $mysql->getMysqli()->insert_id;

    $r = $mysql->query("UPDATE adventure SET main_picture_id={$main_picture_id} WHERE adventure_id={$adventure_id}");
}


for ($i = 0; $i < count($rearrayedPictureFILES) - 1; $i++) {
    $pic = $rearrayedPictureFILES[$i];
    $pic["unique_name"] = generateUniqueImageName($pic["name"],"test",$mysql);

    move_uploaded_file($pic["tmp_name"], "{$_SERVER['DOCUMENT_ROOT']}/uploads/".$pic["unique_name"]);

    $r = $mysql->query("INSERT INTO picture(adventure_id,name)
                   VALUES ('".$adventure_id."','".$pic["unique_name"]."')");
}

if(isset($_POST['tag']))
{
    foreach ($_POST['tag'] as $tag) {
        $r = $mysql->query("INSERT INTO tags(adventure_id,value)
                   VALUES ('".$adventure_id."','".$tag."')");
    }
}


echo $adventure_id;
?>