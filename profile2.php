<?php
$title = "Profile";
require_once("utils/utils.php");
require_once("utils/support_functions.php");
require_once("models/user.class.php");





$query = "SELECT username, name, country FROM users
          WHERE id = '".$_SESSION['id']."'";




//$username = get_current_user($id, $mysql);
//$name = getName($id, $mysql);
//$country = getCountryName($id, $mysql);
//$account = getAccountType($id, $mysql);


require_once("site_body.php");


?>

    <div id="main_container">
        <div id="details_table">
            <table>
                <tr>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Account Type</th>

                </tr>
                <tr>
                    <td>Username...</td>
                    <td>Name...</td>
                    <td>Country...</td>
                    <td>Account Type?...</td>

                </tr>
            </table>
        </div>

        <div id="adventures">
            <ul>
                <li><a href="profile2.php">Adventure #1</a></li>
                <li><a href="profile2.php">Adventure #2</a></li>
                <li><a href="profile2.php">Adventure #3</a></li>
                <li><a href="profile2.php">Adventure #4</a></li>
                <li><a href="profile2.php">Adventure #5</a></li>
            </ul>
        </div>
    </div>



<?php
require_once("site_footer.php");
?>