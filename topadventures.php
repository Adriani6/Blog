<?php
if(count(get_included_files()) ==1) exit("Access denied");

	$adventures = $adv->getLastFiveAdventures(); 
	foreach($adventures as $adve){
		$pictures = $adve['Images'];
		//var_dump($adv->getScore($adve['ID']));
		
			echo "
			<div class='panel panel-default' style='margin-top: 10px;'>
				<div class='panel-heading' style='background-color: orange;'>
					<a href='adventure.php?id={$adve['ID']}'>{$adve['Title']}";

		if($adve['Rating'] != 0)
			echo " (Rating: {$adve['Rating']})";
		else
			echo " (Not rated yet)";

					echo "</a>
					<span style='float:right;' class='glyphicon glyphicon-user'><a href='profile.php?user={$adve['User']}'>{$user->getUsernameFromID($adve['User'])}</a></span>
				</div>
				<div class='panel-body'>
					<span style='float:right;' class='glyphicon glyphicon-globe'>".$a->getCountryNameById($adve['Country'])."</span><hr />
					<div class='row'>"; 
					if(!empty($adve['MainPicture'])){
						echo"<div class='col-md-6 col-md-offset-3'><img src='{$adve['MainPicture']}' alt='{$adve['MainPicture']}' height='300' width='450'></div>
					"; } echo"</div>
					<h4>Description</h4>
					<div class='well well-sm'>
						<span>{$adve['ShortDescription']}...</span>
					</div>
				</div>
			</div>
			";
		
	}
	?>