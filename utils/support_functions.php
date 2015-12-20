<?php
if(count(get_included_files()) ==1) exit("Access denied");

require_once('mysql.php');

function polish($var) {
    return trim(stripslashes(htmlspecialchars($var)));
}

#Two Optional Parameters incase we need a pre-selected option to show up.
# $preValue is the value needed selected, $preSelect wether we want it pre-selected or not, if not, it'll return Undefined as selected.
function renderCountrySelectControl($mySQL, $preSelect = "Unspecified")
{
    echo '
                
    <select name="country" class="form-control" id="country">';

    $result = $mySQL->query("SELECT name from country");
    while ($row = $result->fetch_assoc())
    {
        if($preSelect == $row['name'])
        {
            echo "<option selected='selected' value='".$row['name']."'>" . $row['name'] . "</option>";
        }
        else
        {
            echo "<option value='".$row['name']."'>" . $row['name'] . "</option>";
        }
    }

    echo '
        </select>';
    /*
        <script type="text/javascript">
    $(document).ready(function(){
        $("#country").val("';

    if (isset($_POST['country']))
        echo $_POST['country'];
    else
        echo 'Unspecified';

    echo '");
    });

</script>';
*/
}

function getCountryID($country,$mySQL) {
    $stmt = $mySQL->prepare("SELECT id FROM country WHERE name = ?");
    $stmt->bind_param("s",$country);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0) {
        return null;
    }

    $row = $result->fetch_assoc();
    return $row['id'];
}

function getCountryName($id,$mySQL) {
    $stmt = $mySQL->prepare("SELECT name FROM country WHERE id = ?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0) {
        return null;
    }

    $row = $result->fetch_assoc();
    return $row['name'];
}

function getPictureFilename($id,$mySQL) {
    $id = intval($id);
    $result = $mySQL->query("SELECT name FROM picture WHERE picture_id={$id}");
    if($result->num_rows == 0) {
        return null;
    }

    $row = $result->fetch_assoc();
    return "./uploads/".$row{'name'};
}

function rearrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

// $file - an entry from rearrayFiles($_FILES)
// return true success
// return "<error_message>" error
function validateUploadedImageFile($file) {
    $file['name'] = preg_replace("/[^A-Z0-9._-]/i", "_", $file['name']);

    // check file size before checking file extension
    // exif_imagetype can't check empty files and throws error
    // so it is better to check if tmp_name is set first
    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            return 'No file sent.';
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return "File size is too big. Maximum file size is 3MB. Please edit your file in Paint application and try again.";
        default:
            return "Unknown error occurred while uploading file. Please contact administrator.";
    }

    if (exif_imagetype($file['tmp_name']) != IMAGETYPE_JPEG && exif_imagetype($file['tmp_name']) != IMAGETYPE_PNG) {
        return "File has invalid type - only .jpg and .png file types are allowed.";
    }

    return true;
}

function generateUniqueImageName($filename, $username, $mySQL) {
    $result = $mySQL->query("SELECT picture_id FROM picture ORDER BY picture_id DESC LIMIT 1");
    $row = $result->fetch_assoc();
    // prevent one user to ever overwrite his file
    $magicNumber = 4263912;
    $imageName = ($magicNumber+3*$row['picture_id'])."_by_".$username."_".$filename;

    return $imageName;
}

//$adventure['title']
//$adventure['description']
//$adventure['country']
//$adventure['main_picture']
//$adventure['rating']
//$adventure['user_id']
//$adventure['username']
//$adventure['picture'][]
//$adventure['tag'][]
function getAdventure ($id,$mysql) {
    $stmt = $mysql->prepare("SELECT * FROM adventure WHERE adventure_id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        $adventure['adventure_id'] = $row['adventure_id'];
        $adventure['title'] = $row['title'];
        $adventure['description'] = $row['description'];
        $adventure['country'] = getCountryName($row['country_id'],$mysql);
        $adventure['main_picture'] = getPictureFilename($row['main_picture_id'],$mysql);
        $adventure['rating'] = $row['rating'];
        $adventure['user_id'] = $row['user_id'];

        $result = $mysql->query("SELECT username FROM users WHERE user_id={$row['user_id']}");
        $user_row= $result->fetch_assoc();
        $adventure['username'] = $user_row['username'];

        $result = $mysql->query("SELECT picture_id,name FROM picture where adventure_id={$id}");
        $i = 0;
        while($picture = $result->fetch_assoc()){
            if($picture['picture_id'] != $row['main_picture_id'])
                $adventure['picture'][$i] = "./uploads/".$picture{'name'};
            $i++;
        }

        $result = $mysql->query("SELECT value FROM tags where adventure_id={$id}");
        $i = 0;
        while($tag = $result->fetch_assoc()){
            $adventure['tag'][$i] = $tag["value"];
            $i++;
        }

        $result = $mysql->query("SELECT * FROM comments WHERE adventure_id = {$id} ORDER BY comment_id ASC");

        $comments = array();

        while($comment = $result->fetch_assoc()){
            array_push($comments, $comment);
        }

        $adventure['comments'] = $comments;

        $title = $adventure['title'];
        return $adventure;
    }
    else
        return null;
}


function validateName($name)
{
    if (strlen($name) > 50) {
        return "Name can be maximum 50 characters long.";
    } else if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        return "Name can only contain letters and spaces.";
    }

    return true;
}
?>