
<div id="adventure_container">
    <h4 id="adventure_title"><?php if (isset($adventure["title"])) echo $adventure["title"]; else echo "Title.." ?></h4>
    <h6 id="adventure_country"><?php if (isset($adventure["country"])) echo $adventure["country"]; else echo "Country.." ?></h6>
    <hr />
    <img id="adventure_main_picture" class="img-thumbnail" style="margin-top: 20px; margin-bottom: 20px;"
        <?php if (isset($adventure["main_picture"])) echo "src='".$adventure["main_picture"]."'" ?> />

    <div class="fb-share-button"
         data-href="http://robertmcateer.com" data-layout="button_count">
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
                    echo "<img src='".$pic."'>";
                }
            }
        ?>
    </div>
</div>