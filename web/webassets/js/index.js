var botonesAmount = null;
var otraCantidad = null;
var amount = null;
var botonEnviar = null;

$(document).ready(function(){
    //botonesAmount = $(".js-select-amount");
    otraCantidad = $(".js-add");
    //amount = $("#entordenescompras-num_total");
    botonEnviar = $(".btn_nuevo_monto");

    botonEnviar.on("click", function(e){
        monto = $(".ingreso_monto").val();
        log = $(".ingreso_monto").data("log");
        if(log == "0"){
            window.location.href = baseUrl+"sign-up?monto="+monto;
        }else{
            window.location.href = baseUrl+"site/guardar-orden?monto="+monto;            
        }
    })

    /*otraCantidad.on("change", function(e){
        removerActivar();
        amount.val(otraCantidad.val());
    });*/

    // Al campo de texto número validara solo numeros
	otraCantidad.keydown(function (e) {
		validarSoloNumeros(e);
	})

    /*botonesAmount.on("click", function(e){
        e.preventDefault();
        var elemento = $(this);
        removerActivar()
        activarBoton(elemento);
        var cantidad = $(this).data("value");
        amount.val(cantidad);
        $("form").submit();
    });*/


});

function activarBoton(elemento){
    elemento.addClass("btn-success");
}

function removerActivar(){
    botonesAmount.removeClass("btn-success");
}

/**
 * Valida que cuando se aprieta un boton sea solo números
 *
 * @param e
 */
function validarSoloNumeros(e) {
	// Allow: backspace, delete, tab, escape, enter and .
	if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		// Allow: Ctrl+A, Command+A
		(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
		// Allow: home, end, left, right, down, up
		(e.keyCode >= 35 && e.keyCode <= 40)) {
		// let it happen, don't do anything
		return;
	}
	// Ensure that it is a number and stop the keypress
	if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57))
		&& (e.keyCode < 96 || e.keyCode > 105)) {
		e.preventDefault();
	}
}

 //changed. now with parameter
 function update(slider,val) {
    //changed. Now, directly take value from ui.value. if not set (initial, will use current value.)
    var $amount = val;   
    $("#amount_plan").val($amount);
    $(".js-amount").html($amount);
    $('#slider a').html('<label><span class="glyphicon glyphicon-chevron-left"></span> '+$amount+' <span class="glyphicon glyphicon-chevron-right"></span></label>');
}