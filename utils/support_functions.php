<?php
require_once('mysql.php');

function polish($var) {
    return trim(stripslashes(htmlspecialchars($var)));
}

function renderCountrySelectControl($mySQL)
{
    echo '<div class="form-group">
                <label for="country" class="col-sm-2 control-label">Country</label>
                <div class="col-sm-10">
                    <select name="country" class="form-control" id="country">';

    $result = $mySQL->query("SELECT name from country");
    while ($row = $result->fetch_assoc()) {
        echo "<option>" . $row['name'] . "</option>";
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

</script>
</div>
</div>';
}

?>