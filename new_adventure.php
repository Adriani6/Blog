<?php
$title = "Create new adventure";
require_once("site_body.php");
require_once("models/siteuser.class.php");
$siteUser = new SiteUser($mysql);




?>
    <style>
        .btn-file {
            position: relative;
            overflow: hidden;
        }
        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }
    </style>

<?php
$error = '';

if($siteUser->isLoggedIn() == false){
    $error = "You need to be logged in to add adventures.";
}
else if($siteUser->getType() == "Reader") {
    $error = "'Reader' account types cannot add adventures. Contact administrator to upgrade your account.";
}
else if($siteUser->getType() == "Admin") { // admin can add adventures and can edit any adventure

}
else if(isset($_GET['mode']) && $_GET['mode'] == "edit") // check if it's current author's adventure
{
    $user_id = $siteUser->getUserId();
    $stmt = $mysql->prepare("SELECT * FROM adventure WHERE user_id=? AND adventure_id=?");
    $stmt->bind_param("ii",$user_id,$_POST['adventure_id']);
    $stmt->execute();
    $r = $stmt->get_result();
    if($r->num_rows != 1)
    {
        $error = "You cannot edit this adventure.";
    }
}

if($error != '') {
    echo "<div class='alert alert-danger'>".$error."</div>";
    require_once("site_footer.php");
    exit();
}


if(isset($_GET['mode']) && isset($_GET['id'])) {
    if($_GET['mode'] == 'edit') {
        $id = intval($_GET['id']);
        $result = $mySQL->query("SELECT * FROM adventure WHERE adventure_id = {$id}");
        $edit_adventure = $result->fetch_assoc();
    }
}
?>

    <h4>
        <?php
        if(!isset($edit_adventure['adventure_id']))
            echo "Add adventure";
        else
            echo "Edit adventure";
        ?>


    </h4>
    <hr />

    <div id="add_adventure_success" style="display:none;" class="alert alert-success"></div>
    <div id="add_adventure_fail" style="display:none;" class="alert alert-danger"></div>

    <form class="form-horizontal" action="new_adventure.php" method="POST" enctype="multipart/form-data" id="new_adventure_form">
        <div class="form-group">
            <label for="title" class="col-sm-2 control-label">Title</label>
            <div class="col-sm-10">
                <input type="text" name="title" class="form-control" id="title" placeholder="Title"
                       value="<?php if(isset($edit_adventure['title'])) echo  $edit_adventure['title']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="country" class="col-sm-2 control-label">Country</label>
            <div class="col-sm-10">
                <?php
                if (isset($edit_adventure['country_id']))
                {
                    $name = getCountryName($edit_adventure['country_id'],$mySQL);
                }
                else
                {
                    $name = "Unspecified";
                }

                renderCountrySelectControl($mySQL,$name);
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="title" class="col-sm-2 control-label">Description: </label>
            <div class="col-sm-10">
                <textarea class="form-control" rows="5" name="description" id="description" placeholder="Description"><?php if(isset($edit_adventure['description'])) echo  $edit_adventure['description']; ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Main Picture</label>
            <div class="col-sm-10">
                <span class="btn btn-default btn-file">Upload Picture <input id="main_picture_button" name="main_picture" type="file" /></span>
                <span id="main_picture_value" style="padding-left: 10px;">
                    <?php
                    if(isset($edit_adventure['main_picture_id']))
                    {
                        $result = $mySQL->query("SELECT * FROM picture where picture_id={$edit_adventure['main_picture_id']}");
                        $main_picture = $result->fetch_assoc();

                        echo $main_picture['name'];
                    }
                    ?>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Pictures</label>
            <div class="col-sm-10" id="add_picture_list">
                <?php
                if(isset($edit_adventure['adventure_id']))
                {
                    $result = $mySQL->query("SELECT * FROM picture WHERE adventure_id={$edit_adventure['adventure_id']}");
                    while($picture = $result->fetch_assoc())
                    {
                        if($picture['picture_id'] == $edit_adventure['main_picture_id'])
                            continue;

                        echo '<div class="edit_picture_element">';
                        echo '<span>'.$picture['name'].'</span>';
                        echo '<a class="btn edit_picture_element_remove_btn"><span class="glyphicon glyphicon-remove"></span>Remove</a>';
                        echo '<input type="hidden" name="edit_picture_id[]" value="'.$picture['picture_id'].'"/>';
                        echo '</div>';
                    }
                }
                ?>
                <div class="add_picture_list_element">
                    <div class="add_picture_button_div">
                        <span class="btn btn-default btn-file">Add Picture
                            <input id="add_picture_button" name="picture[]" type="file" />
                        </span>
                    </div>
                    <!-- uploaded picture name is appended here and button becomes hidden -->
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="title" class="col-sm-2 control-label">Tags</label>
            <div class="col-sm-10">
                <div id="add_tag_list">
                    <?php
                        if(isset($edit_adventure['adventure_id']))
                        {
                            $result = $mySQL->query("SELECT * FROM tags WHERE adventure_id={$edit_adventure['adventure_id']}");
                            while($tag = $result->fetch_assoc())
                            {
                                echo '<span class="add_tag_list_element" style="margin: 2px; display: inline-block;">';
                                echo '<input type="hidden"  name="edit_tag_id[]" value="'.$tag['tag_id'].'"/>';
                                echo '<span class="add_tag_value">'.$tag['value'].'</span>';
                                echo '<a class="btn edit_tag_button"><span class="glyphicon glyphicon-remove"></span></a>';
                                echo '</span>';
                            }
                        }
                    ?>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control" id="add_tag_input" placeholder="Tag" />
                    <span class="input-group-btn">
                        <button id="add_tag_button" class="btn btn-default" type="button">Add Tag</button>
                    </span>
                </div>
            </div>
        </div>

        <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />

        <?php
            if(isset($edit_adventure['adventure_id']))
            {
                echo '<input type="hidden" name="edit" value="true" />';
                echo '<input type="hidden" name="adventure_id" value="'.$edit_adventure['adventure_id'].'" />';
            }
        ?>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default" name="create" id="create_button">
                    <?php
                    if(!isset($edit_adventure['adventure_id']))
                        echo "Create";
                    else
                        echo "Edit";
                    ?>
                </button>
            </div>
        </div>
    </form>
        <script type="text/javascript">
            $(document).ready(function() {
                $(".edit_picture_element_remove_btn").click(function() {
                    $(this).parents().closest(".edit_picture_element").remove();
                });

                $(".edit_tag_button").click(function() {
                    $(this).parents().closest(".add_tag_list_element").remove();
                });
            });


            $(document).ready(function() {
                // main picture upload
                $("#main_picture_button").change( function() {
                    $("#main_picture_value").text($("#main_picture_button").val());
                });

                // pictures upload and removal
                $("#add_picture_button").change( function() {
                    var new_picture =
                        $(
                        '<div>' +
                            '<span>' + $(this).val() + '</span>'+
                            '<a class="btn"><span class="glyphicon glyphicon-remove"></span>Remove</a>' +
                        '</div>'
                        );

                    // remove button click
                    $(new_picture).children().last().click(function() {
                        // set value of the file input to nothing because otherwise even after removal it's value is saved in $_FILE
                        $(this).parents().closest(".add_picture_list_element").find("#add_picture_button").val("");

                        $(this).parents().closest(".add_picture_list_element").remove();
                    });

                    // make new copy of the picture input button so it can hold a new value
                    // hide the old one to be kept and processed later in php
                    var copy = $(this).parents().closest(".add_picture_list_element").clone(true);
                    $(this).parents().closest(".add_picture_button_div").hide();
                    copy.find("#add_picture_button").val("");
                    $("#add_picture_list").append(copy);

                    $(this).parents().closest(".add_picture_list_element").append(new_picture);
                });

                $("#add_tag_button").click( function() {
                    if($("#add_tag_input").val().length < 3)
                    {
                        alert("Tags must be at least 3 characters long.");
                        return;
                    }

                    var new_tag =
                        $(
                            '<span class="add_tag_list_element" style="margin: 2px; display: inline-block;">' +
                            '<input type="hidden"  name="tag[]" value="' + $("#add_tag_input").val() + '"/>' +
                            '<span class="add_tag_value">' + $("#add_tag_input").val() + '</span>'+
                            '<a class="btn"><span class="glyphicon glyphicon-remove"></span></a>' +
                            '</span>'
                        );

                    // remove button click
                    $(new_tag).children().last().click(function() {
                        $(this).parents().closest(".add_tag_list_element").remove();
                    });

                    $("#add_tag_list").append(new_tag);
                    $("#add_tag_input").val("");
                });

                // validation..
                $(':file').change(function() {

                });


                $( "#new_adventure_form" ).on( "submit", function( event ) {
                    event.preventDefault();
                    $("#add_adventure_success").hide();
                    $("#add_adventure_fail").hide();

                    var formData = new FormData(this);

                    $.ajax({
                        // The URL for the request
                        url: "<?php echo '~/../ajax/ajax_new_adventure.php';  ?>",

                        // The data to send (will be converted to a query string)
                        data: formData,

                        // Whether this is a POST or GET request
                        type: "POST",

                        // The type of data we expect back
                        dataType : "json",//"json",

                        processData: false,
                        contentType: false,

                        // Code to run if the request succeeds;
                        // the response is passed to the function
                        success: function( result ) {
                            if(result.errors.length == 0)
                            {
                                //$("#add_adventure_success").text(JSON.stringify(result, undefined, 2) + "LEN: " +result.errors.length);
                                if(result.mode == "add")
                                    $("#add_adventure_success").html("Adventure added. Check it out <a target='_blank' href='/adventure.php?id=" + result.adventure_id +"'>here</a>");
                                else if(result.mode == "edit")
                                    $("#add_adventure_success").html("Adventure has been edited. Check it out <a target='_blank' href='/adventure.php?id=" + result.adventure_id +"'>here</a>");

                                $("#add_adventure_success").show();

                                $('html, body').animate({
                                    scrollTop: $("#add_adventure_success").offset().top - 20
                                }, 2000);
                            }
                            else
                            {
                                var errorMsg;
                                if(result.mode == "add")
                                    var errorMsg = "Could not add the adventure. Check the following errors:<br/>";
                                else if(result.mode == "edit")
                                    var errorMsg = "Could not edit the adventure. Check the following errors:<br/>";

                                $.each(result.errors, function() {
                                    errorMsg += "- " + this + "<br/>";
                                });

                                $("#add_adventure_fail").html(errorMsg);
                                $("#add_adventure_fail").show();


                                $('html, body').animate({
                                    scrollTop: $("#add_adventure_fail").offset().top - 20
                                }, 2000);
                            }

                        },


                        // Code to run if the request fails; the raw request and
                        // status codes are passed to the function
                        /*
                        error: function( xhr, status, errorThrown ) {
                            alert( "Sorry, there was a problem!" );
                            console.log( "Error: " + errorThrown );
                            console.log( "Status: " + status );
                            console.dir( xhr );
                        },
                        */


                        // Code to run regardless of success or failure
                        /*
                        complete: function( xhr, status ) {
                            alert( "The request is complete!" );
                        }
                        */
                    });
                });
            });
        </script>


<?php
    require_once("site_footer.php");
?>