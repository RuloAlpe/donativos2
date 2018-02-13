<?php 
use yii\helpers\Url;
use app\models\Pagos;
?>
<html>

<head>

<meta http-equiv="content-type" content="text/html; charset=UTF-8">



<script type="text/javascript">

        $(document).ready(function() {

        	// credenciales para desarrollo

            OpenPay.setId('<?=Pagos::API_OPEN_PAY?>');

            OpenPay.setApiKey('<?=Pagos::API_OPEN_PAY_PUBLIC?>');

			OpenPay.setSandboxMode(<?=Pagos::API_SANDBOX?>);

            //Se genera el id de dispositivo

            var deviceSessionId = OpenPay.deviceData.setup("payment-form", "deviceIdHiddenFieldName");

            

            $('#pay-button').on('click', function(event) {

                event.preventDefault();

                $("#pay-button").prop( "disabled", true);

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

						window.location.replace(baseUrl+'site/gracias');

						

					}else{

						alert("Hubo un problema con el pago");

						$("#pay-button").prop( "disabled", false);

					}

				},error:function(){



					}

			});

              

            };



            var error_callbak = function(response) {

                var desc = response.data.description != undefined ? response.data.description : response.message;

                alert("ERROR [" + response.status + "] " + desc);

                $("#pay-button").prop("disabled", false);

            };



        });

    </script>



<style>

@charset "US-ASCII";



@import "https://fonts.googleapis.com/css?family=Lato:300,400,700";

a.button {

	border-radius: 5px 5px 5px 5px;

	-webkit-border-radius: 5px 5px 5px 5px;

	-moz-border-radius: 5px 5px 5px 5px;

	text-align: center;

	font-size: 21px;

	font-weight: 400;

	padding: 12px 0;

	width: 100%;

	display: table;

	background: #E51F04;

	background: -moz-linear-gradient(top, #E51F04 0%, #A60000 100%);

	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #E51F04),

		color-stop(100%, #A60000));

	background: -webkit-linear-gradient(top, #E51F04 0%, #A60000 100%);

	background: -o-linear-gradient(top, #E51F04 0%, #A60000 100%);

	background: -ms-linear-gradient(top, #E51F04 0%, #A60000 100%);

	background: linear-gradient(top, #E51F04 0%, #A60000 100%);

	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#E51F04',

		endColorstr='#A60000', GradientType=0);

}



a.button i {

	margin-right: 10px;

}



a.button.disabled {

	background: none repeat scroll 0 0 #ccc;

	cursor: default;

}



.bkng-tb-cntnt {

	float: left;

	width: 100%;

}



.bkng-tb-cntnt a.button {

	color: #fff;

	float: right;

	font-size: 18px;

	padding: 5px 20px;

	width: auto;

}



.bkng-tb-cntnt a.button.o {

	background: none repeat scroll 0 0 rgba(0, 0, 0, 0);

	color: #e51f04;

	border: 1px solid #e51f04;

}



.bkng-tb-cntnt a.button i {

	color: #fff;

}



.bkng-tb-cntnt a.button.o i {

	color: #e51f04;

}



.bkng-tb-cntnt a.button.right i {

	float: right;

	margin: 2px 0 0 10px;

}



.bkng-tb-cntnt a.button.left {

	float: left;

}



.bkng-tb-cntnt a.button.disabled.o {

	border-color: #ccc;

	color: #ccc;

}



.bkng-tb-cntnt a.button.disabled.o i {

	color: #ccc;

}



.pymnts {

	float: left;

	width: 100%;

}



.pymnts * {

	float: left;

}



.sctn-row {

	margin-bottom: 35px;

	width: 100%;

}



.sctn-col {

	width: 375px;

}



.sctn-col.l {

	width: 425px;

}



.sctn-col input {

	border: 1px solid #ccc;

	font-size: 18px;

	line-height: 24px;

	padding: 10px 12px;

	width: 333px;

}



.sctn-col label {

	font-size: 24px;

	line-height: 24px;

	margin-bottom: 10px;

	width: 100%;

}



.sctn-col.x3 {

	width: 300px;

}



.sctn-col.x3.last {

	width: 200px;

}



.sctn-col.x3 input {

	width: 210px;

}



.sctn-col.x3 a {

	float: right;

}



.pymnts-sctn {

	width: 100%;

}



.pymnt-itm {

	margin: 0 0 3px;

	padding: 20px 30px;

	width: 100%;

}



.pymnt-itm h2 {

	background-color: #e9e9e9;

	font-size: 24px;

	line-height: 24px;

	margin: 0;

	padding: 28px 0 28px 20px;

	width: 780px;

}



.pymnt-itm.active h2 {

	background-color: #e51f04;

	color: #fff;

	cursor: default;

}



.pymnt-itm div.pymnt-cntnt {

	display: none;

}



.pymnt-itm.active div.pymnt-cntnt {

	background-color: #f7f7f7;

	display: block;

	padding: 0 0 30px;

	width: 100%;

}



.pymnt-cntnt div.sctn-row,
.pymnt-cntnt div.sctn-rows {
	display: flex;
	justify-content: space-between;
	margin: 20px 0;
	width: 100%;
}
.pymnt-cntnt div.sctn-row div.sctn-col,
.pymnt-cntnt div.sctn-row div.sctn-col.l{
	width: 98%;
}
.pymnt-cntnt div.sctn-row div.sctn-col.l{
	margin-right: 4%;
}

.pymnt-cntnt div.sctn-rows div.sctn-col,
.pymnt-cntnt div.sctn-rows div.sctn-col.l
.pymnt-cntnt div.sctn-rows div.sctn-col.cvv{
	width: 48%;
}
.pymnt-cntnt div.sctn-rows div.sctn-col.l{
	display: flex;
	flex-wrap: wrap;
	justify-content: space-between;
}
.pymnt-cntnt div.sctn-rows div.sctn-col:first-child{
	margin-right: 4%;
}
.pymnt-cntnt div.sctn-rows div.sctn-col.l label{
	width: 100%;
}
.pymnt-cntnt div.sctn-rows div.sctn-col.l .sctn-col{
	width: 48%;
}
.pymnt-cntnt div.sctn-rows div.sctn-col.cvv{
	width: 48%;
}
.pymnt-cntnt div.sctn-rows div.sctn-col.cvv .sctn-col{
	width: 100%;
}

.pymnt-cntnt div.sctn-row div.sctn-col.cvv {

	background-image:

		url("<?=Url::base()?>/webassets/images/openpay/cvv.png");

	background-position: 156px center;

	background-repeat: no-repeat;

	padding-bottom: 30px;

}

.openpay {
	align-items: center;
	display: flex;
	justify-content: flex-start;
	width: calc(100% - 160px);
}
.openpay .logo,
.openpay .shield{
	align-items: flex-start;
	display: flex;
	flex-direction: column;
}
.openpay .shield{
	align-items: center;
}
.openpay .logo span{
	text-align: left;
}
.openpay .shield span{
	margin-left: 20px;
	max-width: 330px;
}
.openpay .logo img,
.openpay .shield img{
	margin-top: 10px;
	max-width: 100%;
}
.sctn-rowz{
	display: flex;
	float: right;
	justify-content: center;
	margin-right: 60px;
	margin-top: 24px;
	width: 100px;
}

.sctn-rowz .btn-donar{
	color: #555;
}
.sctn-rowz .btn-donar:active,
.sctn-rowz .btn-donar:focus,
.sctn-rowz .btn-donar:hover{
	background-color: #E26666;
	color: white;
}

.card-expl {
	display: flex;
	justify-content: space-around;
	width: 100%;
}


.card-expl .debit img,
.card-expl .credit img{
	display: block;
	margin-top: 20px;
}

.card-expl .credit img{
	max-width: 100%;
}

.pagocard div{
	cursor: pointer;
}



.card-expl h4 {
	color: #555;
	display: block;
	font-size: 1.35rem;
	font-weight: 400;
	margin: 0;
	/* height: 80px; */
}


.sctn-col label{
	font-size: 1.05rem;
	text-align: left;
}
.sctn-col input[type="text"]{
	font-size: 1.1rem;
    font-family: "Source Sans Pro",sans-serif;
    font-weight: 400;
    padding: 4px 12px;
    text-align: left;
    width: 100%;
}
.sctn-col input[type="text"]::placeholder {
    color: #cac6c6;
    opacity: 1;
}

.sctn-col input[type="text"]:-ms-input-placeholder {
    color: #cac6c6;
}

.sctn-col input[type="text"]::-ms-input-placeholder {
    color: #cac6c6;
}
@media only screen and (max-width: 992px){
	.sctn-col input[type="text"],
	.pymnt-cntnt div.sctn-row div.sctn-col input{
		font-size: 1.1rem;
	    width: 100%;
	}

	.openpay{
		width: calc(100% - 120px);
	}
	.openpay .shield span{
		max-width: 200px;
	}
	.sctn-rowz{
		margin-right: 20px;
		width: 100px;
	}

}
@media only screen and (max-width: 768px){
	.card-expl{
		flex-wrap: wrap;
		justify-content: center;
	}
	.card-expl .debit,
	.card-expl .credit{
		align-items: center;
		display: flex;
		flex-direction: column;
		width: 100%;
	}
	.card-expl .debit img,
	.card-expl .credit img{
		margin-top: 0;
		margin: 0 0 20px;
		max-width: 100%;
	}

	.sctn-col input[type="text"],
	.pymnt-cntnt div.sctn-row div.sctn-col input{
		font-size: 1rem;
	    width: 100%;
	}
	.pymnt-cntnt div.sctn-row div.sctn-col.l{
		margin-bottom: 10px;
	}

	.pymnt-cntnt div.sctn-row,
	.pymnt-cntnt div.sctn-rows{
		margin: 10px 0 0;
	}
	.pymnt-cntnt div.sctn-rows div.sctn-col.cvv{
		margin: 10px 0 10px;
	}

	.sctn-col label{
		margin-bottom: 4px;
	}

	.sctn-col input[type="text"]::placeholder {
	    font-size: .98rem;
	}

	.sctn-col input[type="text"]:-ms-input-placeholder {
	    font-size: .98rem;
	}

	.sctn-col input[type="text"]::-ms-input-placeholder {
	    font-size: .98rem;
	}

	.openpay{
		justify-content: center;
		width: 100%;
	}
	.openpay .logo{
		align-items: center;
		border-right: none;
	}
	.openpay .logo span{
		text-align: center;
	}

	.sctn-rowz{
		float: none;
		width: 100%;
	}
	.sctn-rowz .btn-donar{
		margin-top: 30px;
	}
}
@media only screen and (max-width: 480px){
	.sctn-col input[type="text"],
	.pymnt-cntnt div.sctn-row div.sctn-col input{
		font-size: 1rem;
	    width: 100%;
	}
	.pymnt-cntnt div.sctn-row,
	.pymnt-cntnt div.sctn-rows{
	    flex-wrap: wrap;
	}
	.pymnt-cntnt div.sctn-row .sctn-col,
	.pymnt-cntnt div.sctn-row .sctn-col{
	    width: 100%;
	}

	.sctn-col input[type="text"]::placeholder {
	    font-size: .9rem;
	}

	.sctn-col input[type="text"]:-ms-input-placeholder {
	    font-size: .9rem;
	}

	.sctn-col input[type="text"]::-ms-input-placeholder {
	    font-size: .9rem;
	}

	.pymnt-cntnt div.sctn-row div.sctn-col.l,
	.pymnt-cntnt div.sctn-rows div.sctn-col.l:first-child{
		margin-right: 0;
	}

	.pymnt-cntnt div.sctn-rows{}
	.pymnt-cntnt div.sctn-rows div.sctn-col.l{
		width: 100%;
	}
	.pymnt-cntnt div.sctn-rows div.sctn-col.cvv{
		width: 100%;
	}

	.pymnt-itm.active div.pymnt-cntnt{
		padding: 0;
	}

	.openpay .logo{
		border-right: none;
	}
}


/* modal */
.modal-ticket-op-tc{
	background-color: rgba(0,0,0,0.7);
	bottom: 0;
	height: 100vh;
	left: 0;
	margin: 0;
	position: fixed;
	right: 0;
	top: 0;
	width: 100%;
	z-index: 111;
}
.modal-ticket-op-tc .bkng-tb-cntnt{
	height: 100%;
	position: relative;
	width: 100%;
}
.modal-ticket-op-tc .bkng-tb-cntnt .pymnts{
	align-items: center;
	display: flex;
	height: 100%;
	justify-content: center;
	position: relative;
	width: 100%;
}

.modal-ticket-op-tc .bkng-tb-cntnt .pymnts form{
	background-color: #f7f7f7;
	max-height: 96vh;
	max-width: 1024px;
	overflow-x: auto;
	width: 80%;
}

</style>

</head>

<body>

	<div class="bkng-tb-cntnt">

		<div class="pymnts">

			<form
				<?php
				if($ordenCompra->b_subscripcion){
				?>
				action="<?=Url::base()?>/pagos/crear-subscripcion"
				<?php
				}else{
				?>
				action="<?=Url::base()?>/pagos/pagar-tarjeta-open-pay"
				
				<?php
				}
				?>
				method="POST" id="payment-form">

				<input type="hidden" name="token_id" id="token_id">

				<div class="pymnt-itm card active">

					<div class="pymnt-cntnt">

						<div class="card-expl">

							<div class="debit">

								<h4>Tarjetas de débito</h4>

								<img src="<?=Url::base()?>/webassets/images/openpay/cards1.png" alt="">

							</div>

							<div class="credit">

								<h4>Tarjetas de crédito</h4>

								<img src="<?=Url::base()?>/webassets/images/openpay/cards2.png" alt="">

							</div>

						</div>

						<div class="sctn-row">

							<div class="sctn-col l">

								<label>Nombre del titular</label><input value=""

									type="text" placeholder="Como aparece en la tarjeta"

									autocomplete="off" data-openpay-card="holder_name">

							</div>

							<div class="sctn-col">

								<label>Número de tarjeta</label> <input value="<?=$orderId?>"

									type="hidden" name="orderId"> <input type="text"

									autocomplete="off" data-openpay-card="card_number"

									maxlength="16" value="">

							</div>

						</div>

						<div class="sctn-rows">

							<div class="sctn-col l">

								<label>Fecha de expiración</label>

								<div class="sctn-col half l">

									<input value="" maxlength="2" type="text" placeholder="Mes"

										data-openpay-card="expiration_month">

								</div>

								<div class="sctn-col half l">

									<input value="" maxlength="2" type="text" placeholder="Año"

										data-openpay-card="expiration_year">

								</div>

							</div>

							<div class="sctn-col cvv">

								<label>Código de seguridad</label>

								<div class="sctn-col half l">

									<input value="" type="text" placeholder="3 dígitos"

										autocomplete="off" data-openpay-card="cvv2">

								</div>

							</div>

						</div>

						<div class="openpay">

							<div class="logo">
								<span>
									Transacciones realizadas vía:
								</span>
								<img src="<?=Url::base()?>/webassets/images/openpay/openpay.png") alt="">
							</div>

							<div class="shield">
								<span>
									Tus pagos se realizan de forma segura con encriptación de 256 bits
								</span>
								<img src="<?=Url::base()?>/webassets/images/openpay/security.png" alt="">
							</div>

						</div>

						<div class="sctn-rowz">

							<button class="btn btn-green btn-small btn-donar"

								style="float: right; visibility: visible !important;"

								id="pay-button">Donar</button>

						</div>

					</div>

				</div>

			</form>

		</div>

	</div>
	<a class="close-modal"><i class="ion ion-close"></i></a>
</body>

</html>

