var modal = $("#modal-checkout");
var contenedorAjax = $(".ajax-container");
$(document).ready(function(){
    $(".js-btn-pago").on("click", function(){
        var token = $(this).data("token");
        var tokenOc = $(this).data("tokenoc");
		var tipo = $(this).data("value");
		var tarjeta = $(this).data("tarjeta");
        // abrirModal();
        // colocarProgressBar();
        enviarInformacion(token, tokenOc, tipo, tarjeta);
    });

    

});


$(document).on({
    'click': function(){	window.print();$(".modal-ticket-op").addClass("modal-ticket-op-hide");
    }
}, ".print-btn");

$(document).on({
  'click': function(){
    $(".modal-ticket-op").addClass("modal-ticket-op-hide");
    $(".modal-ticket-op-tc").addClass("modal-ticket-op-hide");
  }
}, ".close-modal");


function abrirModal(){
    
}

function cerrarModal(){ 
    
}

function colocarRespuesta(res){
    contenedorAjax.html(res);
}

function enviarInformacion(token , tokenOc, tipo, tarjeta){
    $.ajax({
        url: baseUrl+"/pagos/generar-orden-compra?token="+tokenOc+"&tc="+tarjeta,
        type: "POST",
        data:{
            formaPago: token
        },
        success:function(res){

            if(tipo==1){
                colocarRespuesta(res);
                $("#form-pay-pal").submit();    
            }else{
                if(!tarjeta){
                    $(".modal-ticket-op").html(res);
                    $(".modal-ticket-op").removeClass("modal-ticket-op-hide");
                }else{
                    $(".modal-ticket-op-tc").html(res);
                    $(".modal-ticket-op-tc").removeClass("modal-ticket-op-hide");
                }    

                
                //abrirModal();
            }
        }
    });
}