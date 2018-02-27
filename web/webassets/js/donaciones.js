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
				|| elemento.hasClass("js-generar-factura") 
				|| elemento.hasClass("js-descargar-pdf") 
				|| elemento.hasClass("js-descargar-xml");

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
				$el.find('.submenu').not($next).slideUp().parent().removeClass('open');
			}
		
		}
		// Elegir submenus multiples (true) submenus uno a la vez (false)
		var accordion = new Accordion($('#accordion'), false);
	});

	$(".js-generar-factura").on("click", function(e){
		e.preventDefault();
		var token = $(this).data("token");
		$("#transaccion").val(token);
		$("#modal-facturacion").show();
	});

	$("#js-generar-factura").on("click", function(e){
		e.preventDefault();
		$("#modal-facturacion").hide();
		var data = $("#form-datos-facturacion").serialize();

		$.ajax({
			url: baseUrl+"pagos/generar-factura",
			data:data,
			method: "POST",
			success:function(r){
				console.log(r);
			}
		});
	});

	$(".js-cancelar-subscripcion").on("click", function(e){
		e.preventDefault();
		var elemento = $(".js-cancelar-subscripcion")
		var s = $(this).data("token");

		$.ajax({
			url:baseUrl+"pagos/borrar-subscripcion-cliente?ids="+s,
			success:function(r){
				if(r.status="success"){
					elemento.replaceWith("Donativo recurrente cancelado");
				}
			}
		});
	});

	$(".js-descargar-pdf").on("click", function(e){
		e.preventDefault();
	});


	$(".js-descargar-xml").on("click", function(e){
		e.preventDefault();
	});


	$(".js-modal-close").on("click", function(e){
		e.preventDefault();
		$("#modal-facturacion").hide();
	});


});