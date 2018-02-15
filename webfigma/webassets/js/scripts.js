$(document).ready(function(){
	console.log("Jquery Running");

	

	window.addEventListener('resize', function () {
		var windowWidth = $(window).width();
		$('.screensize-tag').html(windowWidth + " px");
	});

	// Popup
	// $('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
	// disableOn: 700,
	// type: 'iframe',
	// mainClass: 'mfp-fade',
	// removalDelay: 160,
	// preloader: false,

	// fixedContentPos: false
	// });

	// Modal
	var modal = document.getElementById('modal-video');
	var btn = document.getElementById("open-modal");
	var span = document.getElementsByClassName("modal-close")[0];

	btn.onclick = function() {
		modal.style.display = "-webkit-flex";
		modal.style.display = "flex";
		$("#video")[0].play();
	}

	span.onclick = function() {
		modal.style.display = "none";
		$("#video")[0].load();
	}

	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
			$("#video")[0].load();
		}
	}

});