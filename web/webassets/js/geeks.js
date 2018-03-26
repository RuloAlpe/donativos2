$(document).ready(function(){

    var $viewportMeta = $('meta[name="viewport"]');
    $('input, select, textarea').bind('focus blur', function(event) {
        $viewportMeta.attr('content', 'width=device-width,initial-scale=1,maximum-scale=' + (event.type == 'blur' ? 10 : 1));
        console.log("input focus ios");
    });

    $("input[type=text], input[type=password], input[type=email], textarea").on({ 'touchstart' : function() {
        zoomDisable();
    }});
    $("input[type=text], input[type=password], input[type=email], textarea").on({ 'touchend' : function() {
        setTimeout(zoomEnable, 500);
    }});
    
    function zoomDisable(){
      $('head meta[name=viewport]').remove();
      $('head').prepend('<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />');
    }
    function zoomEnable(){
      $('head meta[name=viewport]').remove();
      $('head').prepend('<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1" />');
    } 


});