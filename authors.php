<?php
include('site_body.php');
//Spacer
echo "<div style='margin-top:20px;'></div>";
$query = "SELECT user_id FROM adventure GROUP BY user_id;";
$contributors = $mySQL->query($query);
$contributors_array = array();
while($obj = $contributors->fetch_assoc()){
	array_push($contributors_array, $obj['user_id']);
}

foreach($contributors_array as $contributor){
	$query2 = "SELECT * FROM users WHERE user_id = '{$contributor}'";
	$result = $mySQL->query($query2);
	while($res = $result->fetch_assoc()){
		echo "
		<div class='panel panel-default'>
		  <div class='panel-body'>
		  <b>".User::getUsernameById($res['user_id'], $mySQL)."</b> ({$res['name']})
		  </div>
		  <div class='panel-footer'>Panel footer</div>
		</div>
		
		
		";
		
		
		
		
		
		
		$res['user_id'];
	}
}



?>