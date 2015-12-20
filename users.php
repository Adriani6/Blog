<?php
include("site_body.php");

echo "<div style='margin-top:20px;'></div>";
echo "<h3>All Users</h3>";
$offset = 0;
$noContributors = 0;

if(isset($_GET['page'])){
	$offset .= 5*$_GET['page'];
}

	$query2 = "SELECT * FROM users";

	$count = $mySQL->query($query2);
	$noContributors = mysqli_num_rows($count);
	
	$query = "SELECT * FROM users LIMIT 5 OFFSET {$offset}";
	$result = $mySQL->query($query);
	
	while($res = $result->fetch_assoc()){
		echo "
		<div class='panel panel-default'>
		  <div class='panel-body'>
		  <b><a href='profile.php?user={$res['user_id']}'>".User::getUsernameById($res['user_id'], $mySQL)."</a></b>";

			if(!empty($res['name'])){
				echo " ({$res['name']})";
			}
		  
			if(!empty($res['country_id']) && $res['country_id'] != 1){
				echo " from {$a->getCountryNameById($res['country_id'], $mySQL)}";
			}
		  
		   echo"
		  </div>
		  <div class='panel-footer'><a href='profile.php?user={$res['user_id']}'>View Profile</a></div>
		</div>
		
		
		";
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

include("site_footer.php");
?>