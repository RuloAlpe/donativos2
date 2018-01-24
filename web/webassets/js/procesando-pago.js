
$(document).ready(function(){
    var token =  $(".pago-pendiente").data("token");
    setInterval(function() {
    $.ajax({
        url: baseUrl+"site/verificar-pago?oc="+token,
        success: function(res){
            if(res=="1"){
                window.location.replace(baseUrl+"site/mis-boletos");
            }
        }
    });
}, 5000);
});
