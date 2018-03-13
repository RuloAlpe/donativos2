<?php 
use yii\helpers\Url;
use app\models\Pagos;
?>
<html>

<head>

<meta http-equiv="content-type" content="text/html; charset=UTF-8">

<script type="text/javascript">
	var btnLadda = $("#pay-button");
	var l = Ladda.create(btnLadda.get(0)); 

        $(document).ready(function() {

        	// credenciales para desarrollo
            OpenPay.setId('<?= Pagos::API_OPEN_PAY ?>');
            OpenPay.setApiKey('<?= Pagos::API_OPEN_PAY_PUBLIC ?>');
			OpenPay.setSandboxMode(<?= Pagos::API_SANDBOX ?>);

            //Se genera el id de dispositivo
            var deviceSessionId = OpenPay.deviceData.setup("payment-form", "deviceIdHiddenFieldName");

            $('#pay-button').on('click', function(event) {

                event.preventDefault();

                l.start();

                OpenPay.token.extractFormAndCreate('payment-form', sucess_callbak, error_callbak);                

            });

            var sucess_callbak = function(response) {
            console.log(response);
            var token_id = response.data.id;
            $('#token_id').val(token_id);
            var forma = $('#payment-form');

			$.ajax({

				url: forma.attr("action"),
				data:forma.serialize(),
				method:"POST",
				success: function(response){
					if(response=="success"){
						swal("Pago correcto","", "success");
						window.location.replace(baseUrl+'site/gracias');
					}else{
						swal("Espera","Hubo un problema con el pago "+ response, "warning");
						l.stop();
					}

				},error:function(){



					}

			});

              

            };



            var error_callbak = function(response) {

                var desc = response.data.description != undefined ? response.data.description : response.message;

                swal("Espera","Hubo un problemo con el pago: ERROR [" + response.status + "] " + desc, "warning");

                l.stop();

            };



        });

    </script>
</head>

<body>



		<form
			<?php
			if ($ordenCompra->b_subscripcion) {
				?>
				action="<?= Url::base() ?>/pagos/crear-subscripcion"
				<?php

			} else {
			?>
				action="<?= Url::base() ?>/pagos/pagar-tarjeta-open-pay"
				
				<?php

			}
			?>
			method="POST" id="payment-form">


			<input type="hidden" name="token_id" id="token_id">
			<input value="<?= $orderId ?>" type="hidden" name="orderId">
			
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="name-card">Nombre titular</label>
						<div class="input-group">
								
								<input id="name-card" class="form-control" value="" type="text" placeholder="Como aparece en la tarjeta" autocomplete="off" data-openpay-card="holder_name">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="number-card">Número de tarjeta</label>
						<div class="input-group">
								
								<input id="number-card" class="form-control" autocomplete="off" data-openpay-card="card_number" value="" maxlength="16">
								<span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="mes-card">Fecha de vencimiento</label>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<div class="input-group">
										<input value="" id="mes-card" class="form-control"  maxlength="2" type="number" placeholder="Mes" data-openpay-card="expiration_month" min="1" max="12">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
									</div>
								</div>	
							</div>
							<div class="col-md-6">
								<div class="input-group">
									<input value="" id="anio-card" class="form-control"  maxlength="2" type="number" placeholder="Año" data-openpay-card="expiration_year" min="18">
									<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				<div class="col-md-3">
					
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="cvv-card">CVV</label>
						<div class="input-group">
							<input value="" id="cvv-card" class="form-control"  type="text" placeholder="3 dígitos" autocomplete="off" data-openpay-card="cvv2">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						</div>
					</div>
				</div>
			</div>	

			<div class="row">
				<div class="col-md-6 col-md-offset-3 text-center">
					<button class="btn btn-success btn-donar btn-block ladda-button" data-style="zoom-in" id="pay-button">
							<span class="ladda-label">Donar</span>
					</button>
				</div>
			</div>
						


						
			<div class="row row-footer">
				<div class="col-sm-6 col-md-6">
					<div class="logo">
						<span>
							Transacciones realizadas vía:
						</span>
						<img src="<?= Url::base() ?>/webassets/images/openpay/openpay.png") alt="">
					</div>
				</div>
				<div class="col-sm-6 col-md-6">
					<div class="shield">
						<span>
							Tus pagos se realizan de forma segura con encriptación de 256 bits
						</span>
						<img src="<?= Url::base() ?>/webassets/images/openpay/security.png" alt="">
					</div>
				</div>
			</div>

		</form>
		
</body>

</html>

