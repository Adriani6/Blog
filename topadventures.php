<?php 
	$adventures = $adv->getLastFiveAdventures(); 
	foreach($adventures as $adve){
		$pictures = $adve['Images'];
		//var_dump($adv->getScore($adve['ID']));
	echo "
	<div class='panel panel-default' style='margin-top: 10px;'>
		<div class='panel-heading' style='background-color: orange;'>
			<a href='adventure.php?id={$adve['ID']}'>".$adve['Title']." (Score: {$adve['Rating']})</a>
			<span style='float:right;' class='glyphicon glyphicon-user'><a href='profile.php?user={$adve['User']}'>{$user->getUsernameFromID($adve['User'])}</a></span>
		</div>
		<div class='panel-body'>
			<span style='float:left;'><a href='adventure.php?id={$adve['ID']}'>View Adventure</a></span>
			<span style='float:right;' class='glyphicon glyphicon-globe'>".$a->getCountryNameById($adve['Country'])."</span><hr />
			<h4>Cover Image</h4>
			<div class='row'><div class='col-md-6 col-md-offset-3'><img src='{$adve['MainPicture']}' height='300px' width='450px'></div></div>
			<h4>Description</h4>
			<div class='well well-sm'>
				<span>{$adve['ShortDescription']}...</span>
			</div>
		</div>
	</div>
	";
		
	}?>