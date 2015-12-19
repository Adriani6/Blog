/**
 * Created by Adriani6 on 5/17/2015.
 */

$(document).ready(function(){
    $(".nav_top_content").click(function(){
        $(".login").slideDown("slow");
		$(".login").css("display", "table");
        $(".nav_top").hide("slow");

    });

    $(document).click(function(e){
        if(!$(e.target).is("#login")) {
            if(!$(e.target).is("input")){
                if ($(".nav_top").is(":hidden")) {
                    $(".login").hide("slow");
                    $(".nav_top").slideDown("slow");
                }
            }
        }
    });
	
});
