$(document).ready(function(){
	console.log("Jquery Running");

	

	window.addEventListener('resize', function () {
		var windowWidth = $(window).width();
		$('.screensize-tag').html(windowWidth + " px");
	});

	

});