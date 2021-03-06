$(document).ready(function(){

	$(function(){
		var Accordion = function(el, multiple) {
			this.el = el || {};
			this.multiple = multiple || false;
	
			// Variables
			var link = this.el.find('.link');
			// Eventos
			link.on('click', {el: this.el, multiple: this.multiple},this.dropdown)
		}
	
		Accordion.prototype.dropdown = function(e) {

			var elemento = $(e.target);
			var isNotToggle = elemento.hasClass("js-cancelar-subscripcion") 
				|| elemento.hasClass("js-generar-factura") ;

			if(isNotToggle){
				return false;
			}

			var $el = e.data.el;
				$this = $(this),
				$next = $this.next();
	
			// Desencadena evento de apertura en los elementos siguientes a la clase link = ul.submenu
			$next.slideToggle();
			// Agregar clase open a elemento padre del elemento con clase link = li
			$this.parent().toggleClass('open');
			
			//Parametro inicial que permite ver 1 solo submenu abierto 
			if(!e.data.multiple){
				$el.find('.donaciones-submenu').not($next).slideUp().parent().removeClass('open');
			}
		
		}
		// Elegir submenus multiples (true) submenus uno a la vez (false)
		var accordion = new Accordion($('#accordion'), false);
	});


	$(".js-generar-factura").on("click", function(e){
		e.preventDefault();
		var token = $(this).data("token");
		$("#transaccion").val(token);
		$("#modal-facturacion").modal("show");
	});



	$("#js-generar-factura").on("click", function(e){
		e.preventDefault();
		
		var data = $("#form-datos-facturacion").serialize();
		var tr = $("#transaccion").val();

		if(!validarRFC($("#entdatosfacturacion-txt_rfc").val())){
			swal("Espera", "RFC no válido", "warning");
			$("#entdatosfacturacion-txt_rfc").val("");
			return false;
		}

		var l = Ladda.create(this);
		 l.start();
		 
		 

		$.ajax({
			url: baseUrl+"pagos/generar-factura",
			data:data,
			method: "POST",
			success:function(r){
				if(r.status=="success"){
					$(".botones-"+tr).html(r.botones);
					$("#modal-facturacion").modal("hide");
					swal("Recibo generado", "Ya puede descargarlo.", "success");
					l.stop();
				}else{
					swal("Espera", "Ocurrio un problema al generar el recibo deducible: "+r.message, "warning");
					l.stop();
				}
				
			}
		});
	});

	// $(".js-cancelar-subscripcion").on("click", function(e){
	// 	e.preventDefault();
	// 	var elemento = $(".js-cancelar-subscripcion")
	// 	var s = $(this).data("token");

	// 	$.ajax({
	// 		url:baseUrl+"pagos/borrar-subscripcion-cliente?ids="+s,
	// 		success:function(r){
	// 			if(r.status="success"){
	// 				elemento.replaceWith("Suspendido");
	// 			}
	// 		}
	// 	});
	// });


	$(".js-cancelar-subscripcion").on("click", function(e){
		e.preventDefault();
		
		var s = $(this).data("token");

		$("#modal-cancelar-donativo").modal("show");
		$("#modal-cancelar-donativo #s").val(s);

		
	});

	$("#js-btn-suspender-subscripcion").on("click", function(e){
		e.preventDefault();
		
		$("#modal-cancelar-donativo form").submit();
	});

	$("#modal-cancelar-donativo form").on("beforeSubmit", function(e){

		
		var s = $("#modal-cancelar-donativo #s").val();
		var boton = $("#js-btn-suspender-subscripcion").get(0);
		var data = $(this).serialize();
		var l = Ladda.create(boton);
		l.start();
		
		$.ajax({
			url:baseUrl+"pagos/borrar-subscripcion-cliente?ids="+s,
			method:"POST",
			data:data,
			success:function(r){
				if(r.status="success"){
					$("#cancelar-"+s).replaceWith("<span class='donaciones-recurrencia-cancelada'>Suspendido</span>");
					l.stop();
					$("#modal-cancelar-donativo").modal("hide");
				}
			},error:function(){
				l.stop();
				$("#modal-cancelar-donativo").modal("hide");
				swal("Espera", "Ocurrió un problema. Si el problema persiste envia un email a comunicacion@figma.org.mx", "warning");
			}
			});
		
		return false;
	});

	$()

	$(".js-modal-close").on("click", function(e){
		e.preventDefault();
		$("#modal-facturacion").hide();
	});

	$("#entdatosfacturacion-txt_rfc").on("change", function(){
		
		if(!validarRFC($(this).val())){
			swal("Espera", "RFC no válido", "warning");
			
			$(this).val("");
		}
	});


});
function validarRFC(string){
	var regex = /^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1]))((-)?([A-Z\d]{3}))?$/g;


	return regex.test(string);
}