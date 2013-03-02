var PREVIEW_FOLDER = "Thumbnails";
var ORIGINS_FOLDER = "UsrImages";
var images_expanded = false;

jQuery(document).ready(function($){

//Function for expanding image
$(".UsrImg").click(function(event){
	var src = $(this).attr('src');
	var subpath = src.split('/');
	var name = subpath[subpath.length-1];
	var folder = subpath[subpath.length-2];
	

	if(folder == PREVIEW_FOLDER) {
		$(this).attr({src: (ORIGINS_FOLDER + '/' + name)});
	} else {
		$(this).attr({src: (PREVIEW_FOLDER + '/' + name)});
	}
});


//Function for expanding images
$(".Expand").click(function(event){
	if(images_expanded == false) {
	$(".UsrImg").each(function(index, elem) {
		var src = $(this).attr('src');
		var subpath = src.split('/');
		var name = subpath[subpath.length-1];
		$(this).attr({src: (ORIGINS_FOLDER + '/' + name)});
	});
	images_expanded = true;
	
	} else {
		$(".UsrImg").each(function(index, elem) {
			var src = $(this).attr('src');
			var subpath = src.split('/');
			var name = subpath[subpath.length-1];
			$(this).attr({src: (PREVIEW_FOLDER + '/' + name)});
		});
		images_expanded = false;
	}
});


//Functions for text formatting
$(".makebold").click(function(event){
	$("textarea").wrapSelected("[b]", "[/b]");
});

$(".makeitalic").click(function(event){
	$("textarea").wrapSelected("[i]", "[/i]");
});

$(".makespoiler").click(function(event){
	$("textarea").wrapSelected("[spoiler]", "[/spoiler]");
});

$(".postNumber").click(function(event){
	$("textarea").val($("textarea").val() + ">>" + $(this).text().slice(2));
});


//Function for showing post on mouseover on it's link
$(".post-link").mouseover(function(event) {
	var link = $(this);
	$.ajax({
		url: "show.php",
		global: false,
		type: "GET",
		data: ({post_ID: link.text().slice(2)}),
		dataType: "html",
		success: function(data) {
			$('#highlight').html(data);
			var measure = $('#highlight').height();
			$('#highlight').show();
			//$('#highlight').css({height: "auto", position: "absolute", left: "-10000px"});
			//alert(measure);
			//calculate new coordinates of answer
			if (link.offset().top - $(window).scrollTop() < $(window).height()/2) {
				var newver = link.offset().top + link.height();
				$('#highlight').offset({top: newver, left: 10});
			} else {
				var newver = link.offset().top - measure - link.outerHeight() ;
				$('#highlight').offset({top: newver, left: 10});
			}
		}
	});
	
	
	

	
	/*if ($(window).width()/2 > event.pageX) {
		var newhor = event.pageX + link.width() + 'px';
		
		$('#highlight').css({left: newhor});
	} else {
		var newhor = $(window).width() - event.pageX - link.width() + 'px';
		$('#highlight').css({right: newhor});
	}*/


	
	//alert($(this).offset()['left']);
	
});

$(".post-link").mouseout(function(event) {
	$('#highlight').removeClass();
	$('#highlight').html(""); 
	$('#highlight').hide();
	$('#highlight').offset({top: 0, left: 0});
});

});
