<div class="panel panel-default" style="margin-top: 20px;">
  <div class="panel-body">
    <div class="page-header">
	  <h1><?php echo $adventure['title']; ?> <small><?php echo $adventure['country']; ?> <div style="float: right;" class="fb-share-button" data-href="http://robertmcateer.com" data-layout="button_count"></div></small></h1>
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
