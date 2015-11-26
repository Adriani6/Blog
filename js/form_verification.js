function verifyPasswords(){
	if(document.getElementById("password").value != "" && document.getElementById("cpassword").value != ""){
		if(document.getElementById("password").value == document.getElementById("cpassword").value){
			document.getElementById("alert").innerHTML = "";
			document.getElementById("register_button").removeAttribute("disabled");
		}else{
			document.getElementById("alert").innerHTML = "<div class='alert alert-danger' role='alert'>Passwords don't match.</div>"
			document.getElementById("register_button").setAttribute("disabled", "disabled");
		}
	}
}

var $input = $('#form');

$input.on('keyup', function () {
		verifyPasswords();
});
