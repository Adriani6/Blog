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
<div class="votingFloatPanel" style="position: fixed; right: 0; margin-right: 15px; margin-top: 15px;">
	<div class="panel panel-primary">
		<div class="panel-body">
			<span style="text-align: center;">
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
						echo "<h3> Your rating: {$adventure['rating']} </h4>";
					?>
				</span>
			</span>
		</div>
	</div>
</div>
<div class="panel panel-default" style="margin-top: 20px;">
  <div class="panel-body">
    <div class="page-header">
	  <h1><?php echo $adventure['title']; ?> <small><?php echo $adventure['country']; ?> 	  
	  <div style="float: right;" class="fb-share-button" data-href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" data-layout="button_count"></div></small></h1>
	</div>
	<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
	  <!-- Indicators -->

	<div class="carousel-inner" role="listbox">
	  <!-- Wrapper for slides --><?php 
			$i = 0;
			foreach($adventure['picture'] as $picture){
				$output = "";
				if($i == 0){
					$output.="<div class='item active' style='height: 500px;'>";
					}else{
						$output.="<div class='item'>";
					}
					
				$output.= "
						<img src='http://blog-dev.azurewebsites.net/{$picture}' height='400px' width='100%' alt='{$picture}'>
						<div class='carousel-caption'>
						<h3>{$adventure['country']}</h3>
						<p>{$adventure['title']}</p>
						</div>
						</div>";

						echo $output;
			} ?>
		  
	</div>
		


	  <!-- Controls -->
	  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	  </a>
	  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	  </a>
	</div>
  </div>
  
  <?php echo "<p style='margin-left: 10px; margin-right: 10px;'> {$adventure['description']} </p>"; ?>
  
</div>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

</div>
