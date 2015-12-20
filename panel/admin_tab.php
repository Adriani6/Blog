<?php
if(count(get_included_files()) ==1) exit("Access denied");

require_once("/../utils/utils.php");

// double check
if($siteUser->getType() != "Admin")
{
    exit();
}


if (isset($_POST['verify']))
{
    $verified_users = array();
    $username = '';

    if(isset($_POST['username']))
    {
        $stmt = $mysql->prepare("UPDATE users SET verified=1 WHERE username=?");
        $stmt->bind_param("s",$username);

        foreach($_POST['username'] as $u)
        {
            $username = $u;
            // ignore all hack attemps, just verify valid usernames
            if ($stmt->execute() == true)
                array_push($verified_users,$username);
        }

        if(!empty($verified_users))
        {
            echo "<div class='alert alert-info'>The following users have been successfully verified:<br/>";
            foreach($verified_users as $u)
                echo "- ".$u."<br/>";
            echo "</div>";
        }
    }
}


?>




<h3>Users awaiting verification</h3>

    <?php

    $result = $mysql->query("SELECT * FROM users WHERE verified=0");

    if($result->num_rows == 0)
        echo "No users are waiting for verification.";
    else
    {
        echo "<form action='usercp.php?tab=admin' method='POST'>";
        echo "<table class='table table-hover'><thead><tr><th>Username</th><th>Name</th><th>Country</th><th>Verify</th></tr></thead><tbody>";
        while($user = $result->fetch_assoc())
        {
            echo "<tr>";
            echo "<td><a href='/../profile.php?user={$user['user_id']}'>{$user['username']}</a></td>";
            echo "<td>{$user['name']}</td>";
            echo "<td>".getCountryName($user['country_id'],$mysql)."</td>";
            echo "<td><div class='checkbox' style='margin: 0 !important;'><label><input name='username[]' type='checkbox' value='{$user['username']}'></label></div></td>";
            echo "</tr>";
        }

        echo "<tr><td></td><td></td><td></td><td><button type='submit' class='btn btn-primary' name='verify'>Verify</button></td></tr>";
        echo "</tbody></table>";
        echo '<div></div>';
        echo "</form>";
    }



    ?>


