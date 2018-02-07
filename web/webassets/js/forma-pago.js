var modal = $("#modal-checkout");
var contenedorAjax = $(".ajax-container");
$(document).ready(function(){
    $(".js-btn-pago").on("click", function(){
        var token = $(this).data("token");
        var tokenOc = $(this).data("tokenoc");
        var tipo = $(this).data("value");
        // abrirModal();
        // colocarProgressBar();
        enviarInformacion(token, tokenOc, tipo);
    });

    $(".js-btn-pago-tarjeta").on("click", function(){
        var token = $(this).data("token");
        var tokenOc = $(this).data("tokenoc");
        var tipo = $(this).data("value");
        // abrirModal();
        // colocarProgressBar();
        enviarInformacionTarjeta(token, tokenOc, tipo);
    });

});

function abrirModal(){
    
}

function cerrarModal(){ 
    
}

function colocarRespuesta(res){
    contenedorAjax.html(res);
}

function enviarInformacion(token , tokenOc, tipo){
    $.ajax({
        url: baseUrl+"/pagos/generar-orden-compra?token="+tokenOc,
        type: "POST",
        data:{
            formaPago: token
        },
        success:function(res){

            if(tipo==1){
                colocarRespuesta(res);
                $("#form-pay-pal").submit();    
            }else{
                $(".modal-ticket-op").html(res);

                $(".modal-ticket-op").removeClass("modal-ticket-op-hide");
                //abrirModal();
            }
        }
    });
}