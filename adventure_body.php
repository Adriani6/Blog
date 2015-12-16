<div class="panel panel-default" style="margin-top: 20px;">
  <div class="panel-body">
    <div class="page-header">
	  <h1><?php echo $adventure['title']; ?> <small><?php echo $adventure['country']; ?> <div style="float: right;" class="fb-share-button" data-href="http://robertmcateer.com" data-layout="button_count"></div></small></h1>
	</div>
	<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
	  <!-- Indicators -->
	  <ol class="carousel-indicators">
	  
	  <?php
	  
			$i = 0;
			foreach($adventure['picture'] as $picture){
				putImage($picture, $adventure['title'], $adventure['country']);
				if($i == 0){
					echo "<li data-target='#carousel-example-generic' data-slide-to='0' class='active'></li>";
				}else{
					echo "<li data-target='#carousel-example-generic' data-slide-to='{$i}'></li>";
				}
				$i++;
			}
	  ?>
	  </ol>

	  <!-- Wrapper for slides -->
	  <div class="carousel-inner" role="listbox">
		<div class="item active">
		  <img src="..." alt="...">
		  <div class="carousel-caption">
			...
		  </div>
		</div>
		<?php 
			function putImage($url, $title, $country){
				echo "<div class='item'>
						<img src='{$url}' alt='{$url}'>
						<div class='carousel-caption'>
						<h3>{$country}</h3>
						<p>{$title}</p>
						</div>
						</div>";
			} ?>
		...
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
</div>

    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
