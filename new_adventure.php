<?php
$title = "Create new adventure";
require_once("site_body.php");
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

    <h4>Add adventure</h4>
    <hr />

    <div id="add_adventure_success" style="display:none;" class="alert alert-success"></div>
    <div id="add_adventure_fail" style="display:none;" class="alert alert-danger"></div>

    <form class="form-horizontal" action="new_adventure.php" method="POST" enctype="multipart/form-data" id="new_adventure_form">
        <div class="form-group">
            <label for="title" class="col-sm-2 control-label">Title</label>
            <div class="col-sm-10">
                <input type="text" name="title" class="form-control" id="title" placeholder="Title"
                       value="<?php if(isset($_POST['title'])) echo  $_POST['title']; ?>">
            </div>
        </div>

        <?php renderCountrySelectControl($mySQL); ?>

        <div class="form-group">
            <label for="title" class="col-sm-2 control-label">Description: </label>
            <div class="col-sm-10">
                <textarea class="form-control" rows="5" name="description" id="description" placeholder="Description"><?php if(isset($_POST['description'])) echo  $_POST['description']; ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Main Picture</label>
            <div class="col-sm-10">
                <span class="btn btn-default btn-file">Upload Picture <input id="main_picture_button" name="main_picture" type="file" /></span>
                <span id="main_picture_value" style="padding-left: 10px;"></span>
            </div>
        </div>

        <div class="form-group">
            <label for="title" class="col-sm-2 control-label">Pictures</label>
            <div class="col-sm-10" id="add_picture_list">
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

        <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default" name="create" id="create_button">Create</button>
            </div>
        </div>

        <script type="text/javascript">
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
                        alert($(this).parents().closest(".add_picture_list_element").find("#add_picture_button").val());
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

                // title preview
                $("#title").keyup(function(){
                    if($(this).val() != "")
                        $("#adventure_title").text($(this).val());
                    else
                        $("#adventure_title").text("Title..");
                })

                // country preview
                $("#country").change(function(){
                    if($(this).val() == "Unspecified")
                        $("#adventure_country").text("Country..");
                    else
                        $("#adventure_country").text($(this).val());
                })

                // description preview
                $("#description").keyup(function(){
                    if($(this).val() != "")
                        $("#adventure_description").text($(this).val());
                    else
                        $("#adventure_description").text("Description..");
                })

                // validation..
                $(':file').change(function() {

                });


                $( "#new_adventure_form" ).on( "submit", function( event ) {
                    event.preventDefault();
                    $("#add_adventure_success").hide();
                    $("#add_adventure_fail").hide();

                    var XX = new FormData(this);

                    $.ajax({
                        // The URL for the request
                        url: "<?php echo '~/../ajax/ajax_new_adventure.php';  ?>",

                        // The data to send (will be converted to a query string)
                        data: XX,

                        // Whether this is a POST or GET request
                        type: "POST",

                        // The type of data we expect back
                        dataType : "html",//"json",

                        processData: false,
                        contentType: false,

                        // Code to run if the request succeeds;
                        // the response is passed to the function
                        success: function( text ) {
                            if(($).isNumeric(text))
                            {
                                $("#add_adventure_success").text("Adventure added. Check it out <a href='/adventure.php?id=" + text + "'>here</a>");
                                $("#add_adventure_success").show();
                            }
                            else
                            {
                                $("#add_adventure_fail").text(text);
                                $("#add_adventure_fail").show();
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
    </form>

<?php
    require_once("adventure_body.php");
    require_once("site_footer.php");
?>