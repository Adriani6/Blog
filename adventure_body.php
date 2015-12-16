<?php
// if this page is included it means that $_GET['adventure_id'] is valid

$error = '';

if(isset($_POST['vote_submit']))
{
    if($siteUser->isLoggedIn() == false)
        $error = "You need to be logged in to vote.";
    else
    {
        $rate = null;
        switch($_POST['vote'])
        {
            case 1: $rate = 1; break;
            case 2: $rate = 2; break;
            case 3: $rate = 3; break;
            case 4: $rate = 4; break;
            case 5: $rate = 5; break;
        }

        if($rate == null)
            $error = "Invalid rate.";
        else
        {
            $stmt = $mysql->prepare("SELECT * FROM votes WHERE user_id=? AND adventure_id=?");
            $stmt->bind_param("ii",$siteUser->getUserId(),$adventure['adventure_id']);
            $stmt->execute();
            $r = $stmt->get_result();

            if($r->num_rows != 0)
                $error = "You already voted for this adventure.";
            else
            {
                // insert vote
                $stmt = $mysql->prepare("INSERT INTO votes(user_id,adventure_id,value) VALUES (?,?,?)");
                $stmt->bind_param("iii",$siteUser->getUserId(),$adventure['adventure_id'],$rate);
                $stmt->execute();

                // calculate new adventure rating
                $stmt = $mysql->prepare("SELECT sum(value),count(value) FROM votes WHERE adventure_id=?");
                $stmt->bind_param("i",$adventure['adventure_id']);
                $stmt->execute();
                $r = $stmt->get_result();
                $row = $r->fetch_row();

                $rating = round($row[0]/$row[1],1);

                // update adventure rating
                $s = $mysql->prepare("UPDATE adventure SET rating=? WHERE adventure_id=?");
                echo $mysql->getMysqli()->error;
                $s->bind_param("di",$rating,$adventure['adventure_id']);
                $s->execute();

                $adventure['rating'] = $rating;
            }
        }
    }

    if($error != '')
        echo "<div class='alert alert-warning'>$error</div>";
    else
        echo "<div class='alert alert-info'>Your vote has been added. Thank you!</div>";
}







?>


<div id="adventure_container">
    <h4 id="adventure_title"><?php if (isset($adventure["title"])) echo $adventure["title"]; else echo "Title.." ?></h4>
    <h6 id="adventure_country">
        <?php
        if (isset($adventure["country"]))
            echo $adventure["country"]." by ".$adventure["username"]."<br>";
        else echo "Country..";

        if (isset($adventure["tag"]))
        {
            echo "Tags: ";
            foreach ($adventure["tag"] as $tag)
            {
                echo $tag." ";
            }
        }
        ?>
    </h6>
    <hr />
    <img id="adventure_main_picture" class="img-thumbnail" style="margin-top: 20px; margin-bottom: 20px;"
        <?php if (isset($adventure["main_picture"])) echo "src='".$adventure["main_picture"]."'" ?> />

    <div class="fb-share-button"
         data-href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" data-layout="button_count">
    </div>

    <div style="text-align: center;">
        <form class="form-inline" action="adventure.php?id=<?php echo $adventure["adventure_id"]; ?>" method="POST" id="form">
            <div class="form-group">
                <select name="vote" class="form-control" id="vote" >
                    <option>5</option>
                    <option>4</option>
                    <option>3</option>
                    <option>2</option>
                    <option>1</option>
                </select>
                <button type="submit" name="vote_submit" class="btn btn-primary"><span class="glyphicon glyphicon-star"></span> Vote</button>
            </div>
        </form>
        <span>
            <?php
            if($adventure['rating'] == 0)
                echo "The adventure has not been rated yet.";
            else
                echo "Rating: {$adventure['rating']}";
            ?>
        </span>
    </div>

    <div id="adventure_description">
        <?php if (isset($adventure["description"])) echo $adventure["description"]; else echo "Description.." ?>
    </div>

    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>


    <div id="adventure_picture_list">
        <?php
            if (isset($adventure["picture"])) {
                foreach($adventure["picture"] as $pic) {
                    echo "<img src='".$pic."'><br>";
                }
            }
        ?>
    </div>
</div>