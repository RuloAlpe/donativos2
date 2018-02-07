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
		
		var l = Ladda.create(this);
	 	l.start();
		
		var form = $("#tipo-pago");
		var data = form.serialize();
		var url = '/global-judging/mexico/community/payments/updateOrdenCompra/t/oc_731a06432268b056c17cb7e6980529fb5a7b262369df1/idToken/con_0d3e5f203a8a868ca2138b890e43899758ab7b2b46494/creditCard/1';
		$.ajax({
			url: url,
			data:data,
			type:"POST",
			dataType:"html",
			success:function(response){
				// cerrarMensajeConfirmacion();
				$('.dgom-ui-opayFormTarjeta-wrapper').html(response);
				l.stop();
			},
			error:function(xhr, textStatus, error){
				//alert("Error");
			},
			statusCode: {
			    404: function() {
			      //alert( "page not found" );
			    },
			    500:function(){
				    //alert("Ocurrio un problema al intentar guardar");
				}
			 }
		});
		
		
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