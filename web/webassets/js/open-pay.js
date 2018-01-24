$(document).ready(function(){
    

    window.addEventListener('resize', function () {
		var windowWidth = $(window).width();
		$('.screensize-tag').html(windowWidth + " px");
	});

	$('.print-btn').click(function (e){
		e.preventDefault();
		window.print();
    });
    
    $('.close-modal').click(function(e){
        $(".modal-ticket-op").addClass("modal-ticket-op-hide");
    });

    


  });