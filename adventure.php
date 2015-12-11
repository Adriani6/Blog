<?php
require_once("utils/utils.php");
require_once("utils/support_functions.php");

$id = intval($_GET['id']);
$result = $mysql->query("SELECT * FROM adventure WHERE adventure_id={$id}");

if($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    $adventure['title'] = $row['title'];
    $adventure['description'] = $row['description'];
    $adventure['country'] = getCountryName($row['country_id'],$mysql);
    $adventure['main_picture'] = getPictureFilename($row['main_picture_id'],$mysql);

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

    $title = $adventure['title'];
}

require_once("site_body.php");
require_once("adventure_body.php");
?>



<?php
require_once("site_footer.php");
?>