<?php
require_once('mysql.php');

function polish($var) {
    return trim(stripslashes(htmlspecialchars($var)));
}

#Two Optional Parameters incase we need a pre-selected option to show up.
# $preValue is the value needed selected, $preSelect wether we want it pre-selected or not, if not, it'll return Undefined as selected.
function renderCountrySelectControl($mySQL, $preValue = "")
{
    echo '
                
    <select name="country" class="form-control" id="country">';

    $result = $mySQL->query("SELECT name from country");
    while ($row = $result->fetch_assoc()) {
		if(!empty($preSelect)){
			if($preValue === $row['name']){
				echo "<option selected>" . $row['name'] . "</option>";	
			}else{
				echo "<option>" . $row['name'] . "</option>";
			}
		}else{
			echo "<option>" . $row['name'] . "</option>";
		}
    }

    echo '
        </select>
        <script type="text/javascript">
    $(document).ready(function(){
        $("#country").val("';

    if (isset($_POST['country']))
        echo $_POST['country'];
    else
        echo 'Unspecified';

    echo '");
    });

</script>';
}

?>