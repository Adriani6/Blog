<?php
require_once('mysql.php');

function polish($var) {
    return trim(stripslashes(htmlspecialchars($var)));
}

#Two Optional Parameters incase we need a pre-selected option to show up.
# $preValue is the value needed selected, $preSelect wether we want it pre-selected or not, if not, it'll return Undefined as selected.
function renderCountrySelectControl($mySQL, $preValue = "")
{
    echo '
                
    <select name="country" class="form-control" id="country">';

    $result = $mySQL->query("SELECT name from country");
    while ($row = $result->fetch_assoc()) {
		if(!empty($preSelect)){
			if($preValue === $row['name']){
				echo "<option selected>" . $row['name'] . "</option>";	
			}else{
				echo "<option>" . $row['name'] . "</option>";
			}
		}else{
			echo "<option>" . $row['name'] . "</option>";
		}
    }

    echo '
        </select>
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
}

function getCountryID($country,$mySQL) {
    $country = $mySQL->getMysqli()->escape_string($country);
    $result = $mySQL->query("SELECT id FROM country WHERE name = '".$country."'");
    if($result->num_rows == 0) {
        return null;
    }

    $row = $result->fetch_assoc();
    return $row['id'];
}

function getCountryName($id,$mySQL) {
    $id = intval($id);
    $result = $mySQL->query("SELECT name FROM country WHERE id = {$id}");
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
// return "1" success
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

    return 1;
}

function generateUniqueImageName($filename, $username, $mySQL) {
    $result = $mySQL->query("SELECT COUNT(picture_id) AS c FROM picture");
    $row = $result->fetch_assoc();
    // prevent one user to ever overwrite his file
    $magicNumber = 4263912;
    $imageName = ($magicNumber+3*$row['c'])."_by_".$username."_".$filename;

    return $imageName;
}

?>