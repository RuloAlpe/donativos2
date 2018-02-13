$(document).ready(function(){
	console.log("Jquery Running");

	

	window.addEventListener('resize', function () {
		var windowWidth = $(window).width();
		$('.screensize-tag').html(windowWidth + " px");
	});

	$('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
		disableOn: 700,
		type: 'iframe',
		mainClass: 'mfp-fade',
		removalDelay: 160,
		preloader: false,

		fixedContentPos: false
	});

});