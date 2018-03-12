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



	// Get the modal
	var modal = document.getElementById('modal-ayuda');

	// Get the button that opens the modal
	var btn = document.getElementById("modal-open");

	// Get the <span> element that closes the modal
	var span = document.getElementsByClassName("modal-close")[0];

	// When the user clicks on the button, open the modal 
	btn.onclick = function() {
		modal.style.display = "-webkit-box";
		modal.style.display = "-ms-flexbox";
		modal.style.display = "flex";
	}

	// When the user clicks on <span> (x), close the modal
	span.onclick = function() {
		modal.style.display = "none";
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
		}
	}




});


$(function () {
	$('#verMas').bind("click", function () {
		var dest = $("#one").offset().top;
		$("html, body").animate({scrollTop: dest},600);
	});

});