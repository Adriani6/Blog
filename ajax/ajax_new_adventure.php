<?php
require_once("/../utils/utils.php");
require_once("/../utils/support_functions.php");


$_POST['title'] = htmlentities($_POST['title']);
$_POST['description'] = htmlentities($_POST['description']);

$result = array();
$errors = array();
$result["errors"] = array();
$result["adventure_id"] = null;

if(isset($_POST['edit']))
    $result["mode"] = "edit";
else
    $result["mode"] = "add";


if($siteUser->isLoggedIn() == false){
    array_push($result["errors"],"You are not logged in. Hint: you can open a new page and log in there then come back and submit again.");
    echo json_encode($result);
    return;
}
else if($siteUser->getType() == "Reader") {
    array_push($result["errors"],"'Reader' account types cannot add adventures. Contact administrator to upgrade your account.");
    echo json_encode($result);
    return;
}
else if($siteUser->getType() == "Admin") { // admin can add adventures and can edit any adventure

}
else if($result["mode"] == "edit") //$siteUser->getType() == "Author"
{
    $stmt = $mysql->prepare("SELECT * FROM adventure WHERE user_id=? AND adventure_id=?");
    $stmt->bind_param("ii",$siteUser->getUserId(),$_POST['adventure_id']);
    $stmt->execute();
    $r = $stmt->get_result();
    if($r->num_rows != 1)
    {
        array_push($result["errors"],"You cannot edit this adventure.");
        echo json_encode($result);
        return;
    }
}

if(strlen($_POST['title']) < 5)
    array_push($errors,"Title must be at least 5 characters long.");
else if(strlen($_POST['title']) > 50)
    array_push($errors,"Title can be maximum 50 characters long.");


$countryId = -1;

if(($countryId = getCountryID($_POST['country'],$mysql)) == null) {
    array_push($errors,"Invalid country.");
}


if(strlen($_POST['description']) < 5)
    array_push($errors,"Description must be at least 10 characters long.");
else if(strlen($_POST['description']) > 1000)
    array_push($errors,"Description can be maximum 1000 characters long.");


$rearrayedPictureFILES = rearrayFiles($_FILES['picture']);
$mainPicture = $_FILES['main_picture'];

if ($mainPicture['name'] != "") {
    $output = validateUploadedImageFile($mainPicture);
    if ($output !== true)
        array_push($errors,"{$mainPicture['name']} - {$output}");
}
else if(!isset($_POST['edit'])){
    array_push($errors,"You need to upload main picture.");
}


// warning: remember to use count -1
// last entry in the array will always be empty because there's always
// one extra file input to add new picture
for ($i = 0; $i < count($rearrayedPictureFILES) - 1; $i++) {
    $output = validateUploadedImageFile($rearrayedPictureFILES[$i]);

    if ($output != 1)
        array_push($errors,"{$rearrayedPictureFILES[$i]['name']} - {$output}");
}

if(isset($_POST['tag']))
{
    for ($i=0; $i < count($_POST['tag']); $i++) {
        $_POST['tag'][$i] = trim($_POST['tag'][$i]);
        $allowed = array("-");
        if (!ctype_alnum(str_replace($allowed, '', $_POST['tag'][$i] ))) {
            array_push($errors,"[{$_POST['tag'][$i]}] tag has invalid value. Tags can only contain characters, digits and the \"-\" symbol.");
            continue;
        }

        if(strlen($_POST['tag'][$i]) < 3)
            array_push($errors,"[{$_POST['tag'][$i]}] tag is too short. Tags must be at least 3 characters long.");
        else if(strlen($_POST['tag'][$i]) > 25)
            array_push($errors,"[{$_POST['tag'][$i]}] tag is too long. Tags can be maximum 25 characters long.");
    }
}

if (!empty($errors))
{
    $result["errors"] = $errors;
    echo json_encode($result);
    return;
}


/***** All form input is valid.
 ***** Attempt to add or edit the adventure.
 *****/
$adventure_id = -1;
$user_id = $siteUser->getUserId();

$stmt = $mysql->prepare("SELECT * FROM adventure WHERE adventure_id = ?");
$stmt->bind_param("i",$_POST['adventure_id']);
$stmt->execute();
$r = $stmt->get_result();
$row = $r->fetch_assoc();

$main_picture_id = $row['main_picture_id'];

if (!isset($_POST['edit'])) // ADDING adventure
{
    $stmt = $mysql->prepare("INSERT INTO adventure(title,country_id,description,user_id) VALUES (?,?,?,?)");
    $stmt->bind_param("sisi",$_POST['title'],$countryId,$_POST['description'],$user_id);
    $stmt->execute();

    $adventure_id = $stmt->insert_id;
}
else // EDITING adventure
{
    $adventure_id = $_POST['adventure_id'];
    $stmt = $mysql->prepare("UPDATE adventure SET title=?,country_id=?,description=? WHERE adventure_id = ?");
    $stmt->bind_param("sisi",$_POST['title'],$countryId,$_POST['description'],$adventure_id);
    $stmt->execute();

    if(!isset($_POST['edit_picture_id']))
    {
        $_POST['edit_picture_id'] = array();
    }

    // update database delete removed pictures
    $stmt = $mysql->prepare("SELECT * FROM picture WHERE adventure_id=?");
    $stmt->bind_param("i",$adventure_id);
    $stmt->execute();
    $r = $stmt->get_result();
    while($picture = $r->fetch_assoc())
    {
        if($picture['picture_id'] == $main_picture_id)
        {
            // picture is the main_picture
            continue;
        }

        $found = 0;
        foreach ($_POST['edit_picture_id'] as $edit_picture_id)
        {
            if($picture['picture_id'] == $edit_picture_id)
            {
                // picture has not been removed
                $found = 1;
                break;
            }
        }

        if($found == 0)
        {
            // picture has been removed
            // unlink file and delete it from database
            unlink("{$_SERVER['DOCUMENT_ROOT']}/uploads/".$picture['name']);
            $stmt = $mysql->prepare("DELETE FROM picture WHERE picture_id=?");
            $stmt->bind_param("i",$picture['picture_id']);
            $stmt->execute();
        }
    }


    if(!isset($_POST['edit_tag_id']))
    {
        $_POST['edit_tag_id'] = array();
    }

    // update database delete removed tags
    $stmt = $mysql->prepare("SELECT * FROM tags WHERE adventure_id=?");
    $stmt->bind_param("i",$adventure_id);
    $stmt->execute();
    $r = $stmt->get_result();
    while($tag = $r->fetch_assoc())
    {
        $found = 0;
        foreach ($_POST['edit_tag_id'] as $edit_tag_id)
        {
            if($tag['tag_id'] == $edit_tag_id)
            {
                // tag has not been removed
                $found = 1;
                break;
            }
        }

        if($found == 0)
        {
            // tag has been removed
            // delete it from database
            $stmt = $mysql->prepare("DELETE FROM tags WHERE tag_id=?");
            $stmt->bind_param("i",$tag['tag_id']);
            $stmt->execute();
        }
    }

}

if($mainPicture['name'] != "")
{
    if (isset($_POST['edit']))
    {
        $stmt = $mysql->prepare("SELECT * FROM picture WHERE picture_id = ?");
        $stmt->bind_param("i",$main_picture_id);
        $stmt->execute();
        $r = $stmt->get_result();
        $main_picture = $r->fetch_assoc();

        unlink("{$_SERVER['DOCUMENT_ROOT']}/uploads/".$main_picture['name']);
        $stmt = $mysql->prepare("DELETE FROM picture WHERE picture_id=?");
        $stmt->bind_param("i",$main_picture['picture_id']);
        $stmt->execute();
    }

    $mainPicture["unique_name"] = generateUniqueImageName($mainPicture["name"],"test",$mysql);
    move_uploaded_file($mainPicture["tmp_name"], "{$_SERVER['DOCUMENT_ROOT']}/uploads/".$mainPicture["unique_name"]);

    $stmt = $mysql->prepare("INSERT INTO picture(adventure_id,name) VALUES (?,?)");
    $stmt->bind_param("is",$adventure_id,$mainPicture["unique_name"]);
    $stmt->execute();
    $main_picture_id = $stmt->insert_id;

    $stmt = $mysql->prepare("UPDATE adventure SET main_picture_id=? WHERE adventure_id=?");
    $stmt->bind_param("ii",$main_picture_id,$adventure_id);
    $stmt->execute();
}


for ($i = 0; $i < count($rearrayedPictureFILES) - 1; $i++) {
    $pic = $rearrayedPictureFILES[$i];
    $pic["unique_name"] = generateUniqueImageName($pic["name"],"test",$mysql);

    move_uploaded_file($pic["tmp_name"], "{$_SERVER['DOCUMENT_ROOT']}/uploads/".$pic["unique_name"]);

    $stmt = $mysql->prepare("INSERT INTO picture(adventure_id,name) VALUES (?,?)");
    $stmt->bind_param("is",$adventure_id,$pic["unique_name"]);
    $stmt->execute();
}

if(isset($_POST['tag']))
{
    foreach ($_POST['tag'] as $tag) {
        $stmt = $mysql->prepare("INSERT INTO tags(adventure_id,value) VALUES (?,?)");
        $stmt->bind_param("is",$adventure_id,$tag);
        $stmt->execute();
    }
}

$result["adventure_id"] = $adventure_id;
echo json_encode($result);

?>