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
    'click': function(e){
        e.preventDefault();	
        $(".print-area").printArea();

        
        
    }
}, ".print-btn");

$(document).on({
  'click': function(){
    
    
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
                    $("#open-pay-ticket .modal-body").html(res);
                    $("#open-pay-ticket").modal("show");
                    //$(".modal-ticket-op").removeClass("modal-ticket-op-hide");
                }else{
                    $("#open-pay-card .modal-body").html(res);
                    $("#open-pay-card").modal("show");
                }    

                
                //abrirModal();
            }
        }
    });
}