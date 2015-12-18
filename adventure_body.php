<?php
// if this page is included it means that $_GET['adventure_id'] is valid

$error = '';

if(isset($_POST['remove_adventure']))
{
	if($siteUser->isLoggedIn() == true && $siteUser->getType() == "Admin")
	{
		// removal process steps:
		// 1) Delete all adventure's pictures filesg
		// 2) Delete all adventure's pictures entries in the database
		// 3) Delete all adventure's tags entries in the database
		// 4) Delete all adventure's comment entries in the database
		// 5) Delete adventure's entry in the database

		// 1) Delete all adventure's pictures files
		$result = $mysql->query("SELECT * FROM picture WHERE adventure_id={$adventure['adventure_id']}");
		while($picture = $result->fetch_assoc())
		{
			// unlink file
			unlink("{$_SERVER['DOCUMENT_ROOT']}/uploads/".$picture['name']);
		}

		// 2) Delete all adventure's pictures entries in the database
		$mysql->query("DELETE FROM picture WHERE adventure_id={$adventure['adventure_id']}");

		// 3) Delete all adventure's tags entries in the database
		$mysql->query("DELETE FROM tags WHERE adventure_id={$adventure['adventure_id']}");

		// 4) Delete all adventure's comment entries in the database
		$mysql->query("DELETE FROM comments WHERE adventure_id={$adventure['adventure_id']}");

		// 5) Delete adventure's entry in the database
		$mysql->query("DELETE FROM adventure WHERE adventure_id={$adventure['adventure_id']}");


		echo "<div class='alert alert-info'>Adventure has been removed.</div>";
		return;
	}
}


if(isset($_POST['vote_submit']))
{
	if($siteUser->isLoggedIn() == false)
		$error = "You need to be logged in to vote.";
	else if($adventure['user_id'] == $siteUser->getUserId())
		$error = "You cannot vote for your own adventure.";
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
			$user_id = $siteUser->getUserId();
			$stmt->bind_param("ii",$user_id,$adventure['adventure_id']);
			$stmt->execute();
			$r = $stmt->get_result();

			if($r->num_rows != 0)
				$error = "You already voted for this adventure.";
			else
			{
				// insert vote
				$stmt = $mysql->prepare("INSERT INTO votes(user_id,adventure_id,value) VALUES (?,?,?)");
				$stmt->bind_param("iii",$user_id,$adventure['adventure_id'],$rate);
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

$error = "";
#Check if POST Request with Comment is received.
if(isset($_POST['post_comment'])){
	//Check if the comment field was empty.
	$len = strlen($_POST['comment']);
	if($len < 10)
		$error = "Comments must be at least 10 characters long.";
	else if($len > 500)
		$error = "Comments can not be longer than 500 characters.";
	else
	{
		//Prepare Statement
		$stmt = $mysql->prepare("INSERT INTO comments (user_id, adventure_id, message) VALUES (?, ?, ?)");
		$user_id = $userObject->getUserId();
		$stmt->bind_param("iis",$user_id,$adventure['adventure_id'],$_POST['comment']);
		$stmt->execute();
	}
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
						echo "<h3> Overall Rating: {$adventure['rating']} </h4>";
					?>
				</span>
			</span>
		</div>
	</div>
</div>
<div class="panel panel-default" style="margin-top: 20px;">
	<div class="panel-body">
		<?php
			if($siteUser->isLoggedIn() == true)
			{
				if($adventure['user_id'] == $siteUser->getUserId() || $siteUser->getType() == "Admin")
				{
					echo "<a class='btn btn-warning' target='blank'
							 href='http://$_SERVER[HTTP_HOST]/new_adventure.php?mode=edit&id={$adventure['adventure_id']}'
							 style='margin-right: 10px;'
							 >Edit Adventure</a>";
				}

				if($siteUser->getType() == "Admin")
				{
					echo "<form action='http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]' method='POST' style='display: inline !important'>";
					echo "<button type='submit' class='btn btn-danger' name='remove_adventure'>Remove adventure</button>";
					echo "</form>";
					//echo "<a target='blank' href='http://$_SERVER[HTTP_HOST]/new_adventure.php?mode=edit&id={$adventure['adventure_id']}'>[edit adventure
				}
			}
		?>
		<div class="page-header">
			<h1><?php echo $adventure['title']; ?> <small><?php echo $adventure['country']; ?>
					<div style="float: right;" class="fb-share-button" data-href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" data-layout="button_count"></div></small></h1>
		</div>
		<?php
		if(isset($adventure['picture']))
		if(count($adventure['picture']) > 0){ ?>
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
	<?php } ?>
	<?php echo "<p style='margin-left: 10px; margin-right: 10px;'> {$adventure['description']} </p>"; ?>
	<blockquote class="blockquote-reverse">
		<p>Posted by <?php echo $adventure['username']; ?></p>
	</blockquote>

</div></div>
<?php
$comments = $adventure['comments'];
foreach($comments as $comment){
	$commentUser = User::getUsernameById($comment['user_id'], $mysql);
	$datetime = new DateTime($comment['date']);
	echo "<div class='panel panel-default'>
		  <div class='panel-body'>
		  {$comment['message']}
		  </div>
		  <div class='panel-footer'>Comment By: <b>{$commentUser}</b> <span>On: <b>{$datetime->format('Y-m-d')}</b> at <b>{$datetime->format('H:i:s')}</b></span>
		  </div>
		</div>";
}
?>
<div class="well">
	<?php if(isset($_SESSION['userClass'])){ ?>
		<form method="POST" action="adventure.php?id=<?php echo $adventure["adventure_id"]; ?>">
			<textarea style="resize:none" class="form-control" name="comment" rows="5"></textarea><br />
			<button name='post_comment' type="submit" class="btn btn-default">Post Comment</button>
		</form>
	<?php }else{ ?>
		<div class="alert alert-warning" role="alert">Please login to post comments.</div>
	<?php } ?>
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
