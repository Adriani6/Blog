<?php 
	require_once '../utils/requests.php'; 
	
	//$tokenUrl = $_SESSION['token'];
	if(empty($_SESSION['ucp'])){
		#header("Location: login.php?err=auth");
	}else{
		unset($_SESSION['ucp']);
	}
	?>

	
<!DOCTYPE html>
<html lang="en">
<head>
<script src="../js/jquery.js"></script>
<script src="../js/html_utils.js"></script>
<link rel="stylesheet" href="../bootstrap_css/bootstrap.min.css">
<script>
var xmlhttp = new XMLHttpRequest();

var url = "../utils/requests.php?data=usercp&token=<?php echo $_SESSION['token']; ?>";

xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        myFunction(xmlhttp.responseText);
    }
}
xmlhttp.open("GET", url, true);
xmlhttp.send();

function myFunction(response) {
    var arr = JSON.parse(response);
    var i;
	var out = "";

	document.getElementById("username").innerHTML = arr[0].Username;
	document.getElementById("name").innerHTML = arr[0].Name;
	document.getElementById("loc").innerHTML = arr[0].Location;
	if(arr[0].Verified == "0"){
		document.getElementById("verified").innerHTML = "Unverified.";
	}else if(arr[0].Verified == "1"){
		document.getElementById("verified").innerHTML = "Verified !";
	}
	
}

//Need to get What is being changed and new data.
$(document).ready(function(){
	$('#save').click(function(){
		var newval = document.getElementById("editedVal").value;
		var dataString = "data=ucpupdate&token=<?php echo $_SESSION['token']; ?>&username=<?php echo $_SESSION['user']; ?>";
		
           $.ajax({
                    type: "GET",
                    url: "../utils/requests.php?",
                    data: {
                        data: dataString
                    },
                
                success:function (msg) {
                    alert("Data Saved: " + msg);
                }
	});
});
});
	var currentTab = "tab1";
	
function changeTab(tabid){
	var newTab = document.getElementById(tabid);
	var oldTab = document.getElementById(currentTab);
	newTab.style.visibility = "visible";
	document.getElementById("nav_tab1").setAttribute("class", "");
	oldTab.style.visibility = "hidden";
	document.getElementById("nav_tab2").setAttribute("class", "active");
	currentTab = tabid;
}

function showEdit(ee){
	var form = document.getElementById("form");
	elementEdited = ee;
	form.innerHTML = "";
	if(ee == "name"){
		form.innerHTML = "Current:<br /><input class='form-control' id='disabledInput' type='text' disabled><br />"+
		"New:<br /><input type='text' class='form-control' id='editedVal' placeholder='New name'><br />"+
		"";
		document.getElementById("disabledInput").value = document.getElementById(ee).innerHTML;
		document.getElementById("edit").style.visibility = "visible";
	}else if(ee == "loc"){
		form.innerHTML = "Current:<br /><input class='form-control' id='disabledInput' type='text' disabled><br />"+
		"New:<br /><input type='text' class='form-control' placeholder='New location'><br />"+
		"<span style='float: right; bottom:0;'><input type='submit' id='save' value='Save'></span>";
		document.getElementById("disabledInput").value = document.getElementById(ee).innerHTML;
		document.getElementById("edit").style.visibility = "visible";		
	}
}
function hideEdit(){
	document.getElementById("edit").style.visibility = "hidden";
}
</script>
</head>
<body>
<div class="container-fluid">	
	<div class="row">
	<div id="edit" style="visibility: hidden; z-index: 1000; position: absolute; margin-top: 10%; width: 100%;">
			<div class="panel panel-default" style="width: 60%; height:300px; margin: 0 auto;">
			  <div class="panel-body">
				Update Detials <span style="float:right;" class="glyphicon glyphicon-remove" onclick="hideEdit()"> </span>
				<hr />
				<form id="form">
				
				</form>
				<span style='float: right; bottom:0;'><div id='save'>Update Details</div></span>
			  </div>
			</div>
	</div>
	<h2 style="margin-left:20px;">User CP</h2>
	  <div class="col-xs-6 col-md-3">
		<ul class="nav nav-pills nav-stacked" style="width: 150px; position: absolute; margin-top:30%;">
			<li role="presentation" id="nav_tab1" class="active"><a href="#" onclick="changeTab('tab1')">Profile</a></li>
			<li role="presentation" id="nav_tab2" onclick="changeTab('tab2')"><a href="#">My Comments</a></li>
			<?php if(isset($_SESSION['type'])){
				if($_SESSION['type'] === "AUTHOR" || $_SESSION['type'] === "ADMIN"){ ?>
			<li role="presentation"><a href="#">My Adventures</a></li>
			<?php } } ?>
			<li role="presentation"><a href="../index.php">Log Out</a></li>
		</ul>
	  </div>
	   <div class="col-xs-12 col-md-9">
	   <!-- Start Profile Tab -->
		<div id="tab1">
			<div id="profile" style="background-color: white; height: 100px; margin-top: 10%; width: 100%;">
			<h3>Profile</h3>
			<table class="table table-hover">
			<tr>
				<td>Username:</td>
				<td><div id="username"></div></td>
				<td></td>
			</tr>
			<tr>
				<td>Full Name:</td>
				<td><div id="name"></div></td>
				<td onclick="showEdit('name')">Edit</td>
			</tr>
			<tr>
				<td>Location:</td>
				<td><div id="loc"></div></td>
				<td onclick="showEdit('loc')">Edit</td>
			</tr>
			<tr>
				<td>Status:</td>
				<td><div id="verified"></div></td>
				<td></td>
			</tr>
			</table>
			
			</div>
		</div>
		<!-- End Profile Tab -->
		<!-- My Comments Tab> -->
		<div id="tab2" style="visibility: hidden;">
		
		</div>
		<!-- My comments end -->
	  </div>
	</div>
</div>
</body>
</html>