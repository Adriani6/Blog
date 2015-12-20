<?php
include('site_body.php');
//Spacer
echo "<div style='margin-top:20px;'></div>";
echo "<h3>Contributors</h3>";
$offset = 0;
$noContributors = 0;

if(isset($_GET['page'])){
	$offset .= 5*$_GET['page'];
}

$noContributors = mysqli_num_rows($mySQL->query("SELECT user_id FROM adventure GROUP BY user_id"));

$query = "SELECT user_id, COUNT(user_id) as count_id FROM adventure GROUP BY user_id LIMIT 5 OFFSET {$offset};";
$contributors = $mySQL->query($query);
$contributors_array = array();
while($obj = $contributors->fetch_assoc()){
	array_push($contributors_array, array('User_Id' => $obj['user_id'], 'Contributions' => $obj['count_id']));
}

foreach($contributors_array as $contributor){
	$query2 = "SELECT * FROM users WHERE user_id = '{$contributor['User_Id']}'";
	
	$result = $mySQL->query($query2);
	while($res = $result->fetch_assoc()){
		echo "
		<div class='panel panel-default'>
		  <div class='panel-body'>
		  <b><a href='profile.php?user={$res['user_id']}'>".User::getUsernameById($res['user_id'], $mySQL)."</a></b>";
		  if(!empty($res['name'])){
			echo " ({$res['name']})"; } 
			if(!empty($res['country_id'])){
			echo " from {$a->getCountryNameById($res['country_id'], $mySQL)}";}
			echo "<span style='float:right;'>Contributions: {$contributor['Contributions']}</span>
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


include ('site_footer.php');
?>