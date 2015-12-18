<?php
include('site_body.php');
//Spacer
echo "<div style='margin-top:20px;'></div>";

$offset = 0;
$noContributors = 0;

if(isset($_GET['page'])){
	$offset .= 5*$page;
}
$noContributors = mysqli_num_rows($mySQL->query("SELECT user_id FROM adventure GROUP BY user_id"));


$query = "SELECT user_id FROM adventure GROUP BY user_id LIMIT 5 OFFSET {$offset};";
$contributors = $mySQL->query($query);
$contributors_array = array();
while($obj = $contributors->fetch_assoc()){
	array_push($contributors_array, $obj['user_id']);
}

foreach($contributors_array as $contributor){
	$query2 = "SELECT * FROM users WHERE user_id = '{$contributor}'";
	$contibutionsCount = mysqli_num_rows($query2);
	$result = $mySQL->query($query2);
	while($res = $result->fetch_assoc()){
		echo "
		<div class='panel panel-default'>
		  <div class='panel-body'>
		  <b>".User::getUsernameById($res['user_id'], $mySQL)."</b> ({$res['name']}) from {$a->getCountryNameById($res['country_id'], $mySQL)}
		  <span style='float:right;'>Contributions: {$contibutionsCount}</span>
		  </div>
		  <div class='panel-footer'><a href='profile.php?user={$res['user_id']}'>View Profile</a></div>
		</div>
		
		
		";
	}
}
?>
	<nav>
		<ul class='pagination'>
	<?php
	$is = false;
	if(isset($_GET['page'])){$is = true;}
	
	if(!$is){
		echo "<li class='disabled'><a href='?page=0' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
	}else{
		echo "<li><a href='?page=0' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
	}
	
	for($i = 0; $i < ($noContributors/5); $i++){
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



?>