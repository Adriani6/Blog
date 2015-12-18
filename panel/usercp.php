<?php 
	require_once '../utils/requests.php'; 
	require_once("../utils/support_functions.php");
	require_once("../utils/utils.php");

	if($siteUser->isLoggedIn() == false)
	{
		echo "Access denied";
		exit();
	}
	else if($siteUser->getType() != "Admin")
	{
		if(isset($_GET['tab']))
		{
			if($_GET['tab'] == "admin")
				$_GET['tab'] = "";
		}
	}
?>

	
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<script src="../js/jquery.js"></script>
<script src="../js/html_utils.js"></script>
<link rel="stylesheet" href="/../bootstrap_css/bootstrap.min.css" >

<<script>
var user = "<?php echo $siteUser->getUsername(); ?>";

function loadDetails(){
	var xmlhttp = new XMLHttpRequest();

	var url = "../utils/requests.php?data=usercp";

	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			parseResponse(xmlhttp.responseText);
		}
	}
	xmlhttp.open("GET", url, true);
	xmlhttp.send();
}

function parseResponse(response) {
    var arr = JSON.parse(response);
    var i;
	var out = "";

	document.getElementById("username").innerHTML = arr[0].Username;
	document.getElementById("name").innerHTML = arr[0].Name;

	$("#country").val(arr[0].Country);
	if(arr[0].Verified == "0"){
		document.getElementById("verified").innerHTML = "Unverified";
	}else if(arr[0].Verified == "1"){
		document.getElementById("verified").innerHTML = "Verified";
	}

	$("#account_type").text(arr[0].Type);
}

//Need to get What is being changed and new data.
$(document).ready(function(){
	$('#save, #updateC').click(function(){
		var change_type = $(this).attr("edit_type");

		
		if(change_type == "country"){
			sendData($("#country").val(), change_type)

		}else if(change_type == "name"){
			var newname = document.getElementById("editedVal").value;
			sendData(newname, change_type);
		}

		function sendData(param_data, param_type){

           $.ajax({

                    url: '../utils/requests.php',
					type: 'POST',
                    data: {
                        val : 'cpupdate',
						change : param_type,
						data : param_data
                    },
			   		dataType: "text",
                success:function (response) {
						alert(response);

					loadDetails();
					hideEdit();
                }
			});
		}
});


	<?php
	if(isset($_GET['tab']) && $_GET['tab'] == "admin")
		echo "changeTab('admin_tab');";
 	?>
});



var currentTab = 'profile_tab';
	
function changeTab(tabid){
	var newTab = document.getElementById(tabid);
	var oldTab = document.getElementById(currentTab);

	document.getElementById("nav_" + currentTab).setAttribute("class", "");
	document.getElementById("nav_" + tabid).setAttribute("class", "active");

	oldTab.style.display = "none";
	newTab.style.display = "block";

	currentTab = tabid;
}

function showEdit(ee){
	var form = document.getElementById("form");
	form.innerHTML = "";
	form.innerHTML = "Current:<br /><input class='form-control' id='disabledInput' type='text' disabled><br />"+
	"New:<br /><input type='text' class='form-control' name='name' id='editedVal' placeholder='New name'><br />"+
	"";
		
	document.getElementById("disabledInput").value = document.getElementById(ee).innerHTML;
	document.getElementById("edit").style.visibility = "visible";
}
function hideEdit(){
	document.getElementById("edit").style.visibility = "hidden";
}
</script>
</head>
<body onload="loadDetails()">
<div class="container-fluid">	
	<div class="row">
	<div id="edit" style="visibility: hidden; z-index: 1000; position: absolute; margin-top: 10%; width: 100%;">
			<div class="panel panel-default" style="width: 60%; height:300px; margin: 0 auto;">
			  <div class="panel-body">
				Update Details <button style="float:right;" class="glyphicon glyphicon-remove" onclick="hideEdit()"> </button>
				<hr />
				<form id="form">
				
				</form>
				<span style='float: right; bottom:0;'><button class="btn btn-default" id='save' edit_type="name">Update Details</button></span>
			  </div>
			</div>
	</div>
	<h2 style="margin-left:20px;">User CP</h2>
	  <div class="col-xs-6 col-md-3">
		<ul class="nav nav-pills nav-stacked" style="width: 150px; position: absolute; margin-top:30%;">
			<li role="presentation" id="nav_profile_tab" class="active" onclick="changeTab('profile_tab')"><a href="#">Profile</a></li>
			<?php
				if($siteUser->getType() == "Admin"){ ?>
			<li role="presentation" id="nav_admin_tab" onclick="changeTab('admin_tab')"><a href="#">Admin Panel</a></li>
			<?php }  ?>
			<li role="presentation"><a href="../index.php">Home</a></li>
			<li role="presentation"><a href="../utils/requests.php?a=logout">Log Out</a></li>
		</ul>
	  </div>
	   <div class="col-xs-12 col-md-9">
		   <!-- Start Profile Tab -->
	   <div id="profile_tab">
			   <div id="profile"">
				   <h3>Profile</h3>
				   <table class="table table-hover">
					   <tr>
						   <td>Username:</td>
						   <td><div id="username"></div></td>
						   <td></td>
					   </tr>
					   <tr>
						   <td>Name:</td>
						   <td><div id="name"></div></td>
						   <td><button type="button" class="btn btn-default" onclick="showEdit('name')">Edit</button></td>
					   </tr>
					   <tr>
						   <td>Country:</td>
						   <td><div id="loc"><?php renderCountrySelectControl($mySQL); ?></div></td>
						   <td><button type="button" class="btn btn-default" id="updateC" edit_type="country">Update</button></td></td>
					   </tr>
					   <tr>
						   <td>Status:</td>
						   <td><div id="verified"></div></td>
						   <td></td>

					   <tr>
						   <td>Account type:</td>
						   <td><div id="account_type"></div></td>
						   <td></td>
					   </tr>
				   </table>

			   </div>
		   </div>
		   <!-- End Profile Tab -->
		   <!-- Start Admin Tab> -->
		   <div id="admin_tab" style="display: none;">
			   <?php
			   if($siteUser->getType() == "Admin")
			   {
				   include("admin_tab.php");
			   }
			   ?>
		   </div>
		<!-- End Admin Tab -->
	  </div>
	</div>
</div>
</body>
</html>