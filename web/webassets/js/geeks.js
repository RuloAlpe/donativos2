$(document).ready(function(){

    var $viewportMeta = $('meta[name="viewport"]');
    $('input, select, textarea').bind('focus blur', function(event) {
        $viewportMeta.attr('content', 'width=device-width,initial-scale=1,maximum-scale=' + (event.type == 'blur' ? 10 : 1));
        console.log("input focus ios");
    });


});