$(document).ready(function(){
	console.log("Jquery Running");

	window.addEventListener('resize', function () {
		var windowWidth = $(window).width();
		$('.screensize-tag').html(windowWidth + " px");
	});

	var owl = $('.owl-carousel');
	owl.owlCarousel({
		items:1,
		loop:true,
		margin:0,
		autoplay:true,
		autoplayTimeout:4000,
		autoplayHoverPause:true
	});

	// Masonry grid setup
	// $(".grid").masonry({
	// 	itemSelector: ".grid__item",
	// 	columnWidth: ".grid__sizer",
	// 	gutter: 15,
	// 	percentPosition: true
	// });
	// Image replacement handler
	// $(document).on("click", ".js-button", function() {
	// 	var imageSrc = $(this).parents(".grid__item").find("img").attr("src");
	// 	$(".js-download").attr("href", imageSrc);
	// 	$(".js-modal-image").attr("src", imageSrc);
	// 	$(document).on("click", ".js-heart", function() {
	// 		$(this).toggleClass("active");
	// 	});
	// });

});