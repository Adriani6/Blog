<?php
$title = "Home";
require_once("site_body.php");
?>

    <form class="form-horizontal" action="search.php" method="POST" id="form">
        <div class="">
            <br>
            Fill in all inputs or leave some empty.
            <br>
            <br>
        </div>
        <div class="form-group">
            <label for="title" class="col-sm-2 control-label">Title</label>

            <div class="col-sm-10">
                <input type="text" name="title" class="form-control" id="title" placeholder="Partial or whole title"
                       value="<?php if (isset($_POST['title'])) echo $_POST['title']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="author" class="col-sm-2 control-label">Author</label>

            <div class="col-sm-10">
                <input type="text" name="author" class="form-control" id="author" placeholder="Author"
                       value="<?php if (isset($_POST['author'])) echo $_POST['author']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="tags" class="col-sm-2 control-label">Tags</label>

            <div class="col-sm-10">
                <input type="text" name="tags" class="form-control" id="tags"
                       placeholder="Example: rock-climing, desert, bicycle"
                       value="<?php if (isset($_POST['tags'])) echo $_POST['tags']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="min_rating" class="col-sm-2 control-label">Minimum Rating:</label>

            <div class="col-sm-10">
                <input type="text" name="min_rating" class="form-control" id="tags" style="width: 70px;"
                       placeholder="Example: rock-climing, desert, bicycle"
                       value="<?php if (isset($_POST['min_rating'])) echo $_POST['min_rating']; else echo '1'; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="max_rating" class="col-sm-2 control-label">Maximum Rating:</label>

            <div class="col-sm-10">
                <input type="text" name="max_rating" class="form-control" id="tags" style="width: 70px;"
                       value="<?php if (isset($_POST['max_rating'])) echo $_POST['max_rating']; else echo '5' ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="unrated_adventures" class="col-sm-2 control-label">Include: </label>

            <div class="col-sm-10">
                <div class="checkbox">
                    <label><input type="checkbox" name="unrated_adventures" id="unrated_adventures" checked>Unrated Adventures</label>
                </div>
            </div>
        </div>



        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default" name="search" id="search_button">Search</button>
            </div>
        </div>
    </form>

<?php

if (isset($_POST['search'])) {
    $search_query = "SELECT * FROM adventure WHERE ";
// add "AND" before condition if it is not the first one
    $first_condition = true;

    if (!empty($_POST['title'])) {
        // Make title query a bit less restrict. Place "%" between every word.
        $title = str_replace(',', " ", $_POST['title']);
        $title = explode(" ", $title);
        $query = "%";
        foreach ($title as $part) {
            $query .= "{$part}%";
        }

        if (!$first_condition)
            $search_query .= "AND ";
        $search_query .= 'title LIKE "' . $query . '" ';

        $first_condition = false;
        //echo $search_query;
    }

    if (!empty($_POST['author'])) {
        $stmt = $mysql->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s",$_POST['author']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows != 1) {
            echo "<div class='alert alert-warning'>No results were found. Please update parameteres and try again. </div>";
            require_once("site_footer.php");
            exit();
        }
        $row = $result->fetch_assoc();

        if (!$first_condition)
            $search_query .= " AND ";
        $first_condition = false;

        $search_query .= 'user_id = ' . $row['user_id'] . ' ';

        //echo $search_query;
    }

    if (!empty($_POST['tags'])) {
        $tags = str_replace(" ", "", $_POST['tags']);
        $tags = explode(",", $tags);

        foreach ($tags as $tag) {
            $tag = $mysql->getMysqli()->real_escape_string($tag);

            if (!$first_condition)
                $search_query .= "AND ";
            $first_condition = false;

            $search_query .= "adventure_id IN (SELECT adventure_id FROM tags WHERE value='{$tag}') ";
        }
    }

    $min_rating = 0;

    if (!empty($_POST['min_rating'])) {
        $min_rating = floatval($_POST['min_rating']);
        if($min_rating >= 1 && $min_rating <= 5)
        {
            if (!$first_condition)
                $search_query .= "AND ";
            $first_condition = false;

            if (isset($_POST['unrated_adventures']))
                $search_query .= "(rating = 0 or (rating >= $min_rating ";
            else
                $search_query .= "rating >= $min_rating ";
        }
        else
            $min_rating = 0;
    }

    if (!empty($_POST['max_rating'])) {
        $max_rating = floatval($_POST['max_rating']);
        if($max_rating >= 1 && $max_rating <= 5)
        {
            if (!$first_condition)
                $search_query .= "AND ";
            $first_condition = false;

            if (isset($_POST['unrated_adventures']))
            {
                if($min_rating == 0)
                    $search_query .= "rating <= $max_rating ";
                else
                    $search_query .= "rating <= $max_rating ))";
            }
            else
                $search_query .= "rating <= $max_rating ";
        }
    }

    if(!$first_condition)
    {
        $result = $mySQL->query($search_query);
    }

    if (!$first_condition && $result->num_rows > 0) {
        echo "<div class='alert alert-info'>Found {$result->num_rows} results.</div>";

        while ($row = $result->fetch_assoc()) {
	echo "
	<div class='panel panel-default' style='margin-top: 10px;'>
		<div class='panel-heading' style='background-color: orange;'>
			<a href='adventure.php?id={$row['adventure_id']}'>".$row['title'];
            if($row['rating'] != 0)
                echo " (Rating: {$row['rating']})";
            else
                echo " (Not rated yet)";

			echo "</a>
			<span style='float:right;' class='glyphicon glyphicon-user'><a href='profile.php?user={$row['user_id']}'>{$user->getUsernameFromID($row['user_id'])}</a></span>
		</div>
		<div class='panel-body'>
			<span style='float:right;' class='glyphicon glyphicon-globe'>".$a->getCountryNameById($row['country_id'])."</span><hr />
			<img src='".getPictureFilename($row['main_picture_id'],$mysql)."' height='300px' width='450px'>
			<h4>Description</h4>
			<div class='well well-sm'>
				<span>{$adv->createShortDescription($row['description'])}...</span>
			</div>
		</div>
	</div>";  }
    }
    else {
        echo "<div class='alert alert-warning'>No results were found. Please update parameteres and try again. </div>";
    }
}


require_once("site_footer.php");
?>