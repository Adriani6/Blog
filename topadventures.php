<?php 
	$adventures = $adv->getLastFiveAdventures(); 
	foreach($adventures as $adve){
		$pictures = $adve['Images'];
	echo "
	<div class='panel panel-default' style='margin-top: 10px;'>
		<div class='panel-heading' style='background-color: orange;'>
			<a href='adventure.php?id={$adve['ID']}'>".$adve['Title']."</a>
			<span style='float:right;' class='glyphicon glyphicon-user'>{$user->getUsernameFromID($adve['User'])}</span>
		</div>
		<div class='panel-body'>
			<span style='float:left;'><a href=''>View Adventure</a></span>
			<span style='float:right;' class='glyphicon glyphicon-globe'>".$a->getCountryNameById($adve['Country'])."</span><hr />
			<h4>Cover Image</h4>
			<img src='{$adve['MainPicture']}' height='300px' width='450px'>
			<h4>Description</h4>
			<div class='well well-sm'>
				<span>{$adve['ShortDescription']}...</span>
			</div>
		</div>
	</div>
	";
		
	}?>