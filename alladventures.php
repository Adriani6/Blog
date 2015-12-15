<?php
	include("site_body.php");
	$offset = 0;
	$noAdventures = $adv->countAdventures();
	if(isset($_GET['page'])){$offset = intval($_GET['page'])*5;}
	
	$adventures = $adv->getOffsetAdventures($offset); 
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
	</div>";  } 

	echo "
	<nav>
		<ul class='pagination'>
	";
	$is = false;
	if(isset($_GET['page'])){$is = true;}
	
	if(!$is){
		echo "<li class='disabled'><a href='?page=0' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
	}else{
		echo "<li><a href='?page=0' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
	}
	
	for($i = 0; $i < ($noAdventures/5); $i++){
		if($is){
			if($_GET['page'] == $i){
				echo "<li class='active'><a href='?page={$i}'>{$i} <span class='sr-only'>(current)</span></a></li>";
			}else{
				echo "<li><a href='?page={$i}'>{$i} <span class='sr-only'></span></a></li>";
			}
		}else{
			if($i == 0){
				echo "<li class='active'><a href='?page={$i}'>{$i} <span class='sr-only'>(current)</span></a></li>";
			}else{
				echo "<li><a href='?page={$i}'>{$i} <span class='sr-only'></span></a></li>";
			}
		}
	
	}
	echo "  </ul>
</nav>	";

	
	
	include("site_footer.php");
?>