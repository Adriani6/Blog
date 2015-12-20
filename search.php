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
        $result = $mySQL->query("SELECT user_id FROM users WHERE username='{$_POST['author']}'");
        if ($result->num_rows != 1) {
            echo "NO AUTHOR";
            exit(); // change later
        }
        $row = $result->fetch_assoc();
        //echo "ALTOR: ".$row['user_id'];

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
            if (!$first_condition)
                $search_query .= "AND ";
            $first_condition = false;

            $search_query .= "adventure_id IN (SELECT adventure_id FROM tags WHERE value='{$tag}') ";
        }
    }

    //echo $search_query;
    if(!$first_condition){
        $result = $mySQL->query($search_query);
    }

    if (!$first_condition && $result->num_rows > 0) {
        echo "<div class='alert alert-info'>Found {$result->num_rows} results.</div>";

        while ($row = $result->fetch_assoc()) {
	echo "
	<div class='panel panel-default' style='margin-top: 10px;'>
		<div class='panel-heading' style='background-color: orange;'>
			<a href='adventure.php?id={$row['adventure_id']}'>".$row['title']."(Score: {$row['rating']})</a>
			<span style='float:right;' class='glyphicon glyphicon-user'><a href='profile.php?user={$row['user_id']}'>{$user->getUsernameFromID($row['user_id'])}</a></span>
		</div>
		<div class='panel-body'>
			<span style='float:right;' class='glyphicon glyphicon-globe'>".$a->getCountryNameById($row['country_id'])."</span><hr />
			<div class='row'>
			<div class='col-md-6 col-md-offset-3'>
			<img src='{$row['main_picture_id']}' height='300' width='450'></div></div>
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