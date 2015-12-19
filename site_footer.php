
</div>
   </div> 

    </div> <!-- row -->
</div> <!-- container -->
	<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script>
	$(document).ready(function(){
		$('.dropdown-toggle').dropdown()
		$("#calendar").click(function(){
			$("#calendar").datepicker();
		});
	});
	</script>
    
    <script src="bootstrap_js/bootstrap.min.js"></script>
    <script src="js/html_utils.js"></script>
    <script src="js/effects/effects.js"></script>

    <!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent -->
    <script type="text/javascript">
    //    window.cookieconsent_options = {"message":"This website uses cookies to ensure you get the best experience on our website","dismiss":"Got it!","learnMore":"More info","link":"https://www.cookielaw.org/the-cookie-law/","theme":"light-floating"};
    </script>
	<script>
	$(document).ready(function(){
		$('#search').click(function(){
			if($('#search').hasClass('glyphicon-search')){
				$('#search').toggleClass('glyphicon-search glyphicon-remove');
				$('#navSearchBox').css('visibility', 'visible');
			}else{
				$('#search').toggleClass('glyphicon-remove glyphicon-search');
				$('#navSearchBox').css('visibility', 'hidden');	
			}
		});
	});
	</script>
    <!--<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/cookieconsent.min.js"></script>
		End Cookie Consent plugin -->
	<div class="footer">
	<div class="container-fluid">
		<div class="row">
		  <div class="col-md-6 col-md-offset-3" style='margin-top: 15px; color: white; font-size: 18px;'>
		  All images and any other content could be copyrighted by their authors.<br /><br />
		  Designed and built by Krzysztof, Adrian, Robert and John.<br />
		  Here for Beer &copy; 2015
		  <span style='float: right;'>
			<a href='https://github.com/Adriani6/Blog'>Github</a>
		  </span>
		  </div>
		</div>
		</div>
	</div>
</body>
</html>