
<?php
	$link = "";
	
	include("site_body.php");
	echo "<div id='subNav' style='margin-top: 0px; padding-top:0px; margin-top: 15px; line-height: 50px;'>
	<span>Toolbar</span>
	<span style='float:right; line-height:50px;'>
		<div class='dropdown'>
		<button class='btn btn-default dropdown-toggle' type='button' id='dropdownMenu1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
			Sort By
			<span class='caret'></span>
		</button>
		<ul class='dropdown-menu' aria-labelledby='dropdownMenu4'>
		  <li class='dropdown-header'>Sorting Options</li>
		  <li role='separator' class='divider'></li>
		  <li><a href='?&sort=newest'>Newest</a></li>
		  <li><a href='?sort=oldest'>Oldest</a></li>
		  <li role='separator' class='divider'></li>
		  <li><a href='?sort=top'>Top Rated</a></li>
		  <li><a href='?sort=bottom'>Least Rated</a></li>
		</ul>
		</div>
	</span>
	</div>";
	
	$offset = 0;
	$noAdventures = $adv->countAdventures();
	if(isset($_GET['page'])){
		$offset = intval($_GET['page'])*5;
	}
	
	if(isset($_GET['sort'])){
		$link = "&sort=".$_GET['sort'];	
	}
	
	$adventures;
	
	if(isset($_GET['page'], $_GET['sort'])){
		$adventures = $adv->getOffsetAdventures($offset, $_GET['sort']);
	}else if(isset($_GET['page'])){
		$adventures = $adv->getOffsetAdventures($offset); 
	}else if(isset($_GET['sort'])){
		$adventures = $adv->getOffsetAdventures($offset, $_GET['sort']);
	}else{
		$adventures = $adv->getOffsetAdventures($offset); 
	}

	foreach($adventures as $adve){
		$pictures = $adve['Images'];
	echo "
	<div class='panel panel-default' style='margin-top: 10px;'>
		<div class='panel-heading' style='background-color: orange;'>
			<a href='adventure.php?id={$adve['ID']}'>".$adve['Title'];

		if($adve['Rating'] != 0)
			echo " (Rating: {$adve['Rating']})";
		else
			echo " (Not rated yet)";

			echo "</a>
			<span style='float:right;' class='glyphicon glyphicon-user'><a href='profile.php?user={$adve['User']}'>{$user->getUsernameFromID($adve['User'])}</a></span>
		</div>
		<div class='panel-body'>
			<span style='float:right;' class='glyphicon glyphicon-globe'>".$a->getCountryNameById($adve['Country'])."</span><hr />
			<div class='row'>
			<div class='col-md-6 col-md-offset-3'><img src='{$adve['MainPicture']}' height='300px' width='450px'></div></div>
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
		echo "<li class='disabled'><a href='?page=0{$link}' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
	}else{
		echo "<li><a href='?page=0' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
	}
	
	for($i = 0; $i < ($noAdventures/5); $i++){
		if($is){
			if($_GET['page'] == $i){
				echo "<li class='active'><a href='?page={$i}{$link}'>{$i} <span class='sr-only'>(current)</span></a></li>";
			}else{
				echo "<li><a href='?page={$i}{$link}'>{$i} <span class='sr-only'></span></a></li>";
			}
		}else{
			if($i == 0){
				echo "<li class='active'><a href='?page={$i}{$link}'>{$i} <span class='sr-only'>(current)</span></a></li>";
			}else{
				echo "<li><a href='?page={$i}{$link}'>{$i} <span class='sr-only'></span></a></li>";
			}
		}
	
	}
	echo "  </ul>
</nav>	";
	include("site_footer.php");
?>