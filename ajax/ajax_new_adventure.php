<?php
print_r($_FILES);


for($i=0; $i<count($_FILES['picture']['name']); $i++) {
    //Get the temp file path
    $tmpFilePath = $_FILES['picture']['tmp_name'][$i];

    //Make sure we have a filepath
    if ($tmpFilePath != ""){
        //Setup our new file path
        $newFilePath = "{$_SERVER['DOCUMENT_ROOT']}\\uploads\\" . $_FILES['picture']['name'][$i];

        //Upload the file into the temp dir
        if(move_uploaded_file($tmpFilePath, $newFilePath)) {

            echo "OK: {$newFilePath}<br>";

        }
    }
}

?>