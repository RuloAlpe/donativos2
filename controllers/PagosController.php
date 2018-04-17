<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use vendor\facebook\FacebookI;
use app\models\Pagos;
use app\modules\ModUsuarios\models\Utils;
use app\models\CatTiposPago;
use app\models\EntOrdenesCompras;
use yii\web\BadRequestHttpException;
use app\models\EntPagosRecibidos;
use app\models\IPNPayPal;
use app\modules\ModUsuarios\models\EntUsuarios;
use app\models\EntBoletos;
use app\models\EntTarjetas;
use app\models\EntSubscripciones;
use yii\web\Response;
use app\models\EntDatosFacturacion;
use yii\helpers\Url;

class PagosController extends Controller
{

	public $enableCsrfValidation = false;
    /**
     * @inheritdoc
     */
    public function behaviors() {
		return [ 
				'access' => [ 
						'class' => \yii\filters\AccessControl::className (),
						'only' => [ 
								'generar-ticket-open-pay',
                                'forma-pago',
                                'seleccionar-producto',
                                'generar-orden-compra',
                                'pagar-tarjeta-open-pay'
						],
						'rules' => [
								
								// allow authenticated users
								[ 
										'allow' => true,
										'roles' => [ 
												'@' 
										] 
								] 
						] 
				] 
		];
		// everything else is denied
	}

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Genera la orden de compra
     */
    public function actionGenerarOrdenCompra($token=null, $tc=false){

        if(isset($_POST['formaPago'])  ){
           
            $ordenCompra = $this->findOrdenCompra($token);
            $formaPago = $this->getFormaPagoByToken($_POST['formaPago']);
            $ordenCompra->id_tipo_pago = $formaPago->id_tipo_pago;
            $ordenCompra->save();
            return $this->vistaPago($ordenCompra, $tc);

        }

    }

    /**
     * Busca la orden de compra en la base de datosd.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return EntVoluntario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
     protected function findOrdenCompra($token)
     {
         if (($model = EntOrdenesCompras::findOne(["txt_order_number"=>$token])) !== null) {
             return $model;
         } else {
             throw new NotFoundHttpException('The requested page does not exist.');
         }
     }

	

	/**
	 * IPN para payl pal
	 */
	public function actionIpnPaypal() {
		$payPal = new IPNPayPal ();
		$payPal->payPalIPN ();
	}

    private function vistaPago($ordenCompra, $tc=false){
        
        switch ($ordenCompra->id_tipo_pago){
			case 1:
                return $this->renderAjax('formPayPal', ['ordenCompra'=>$ordenCompra]);
            break;
			case 2:
			
				if($tc){
					$ordenCompraTarjetaCredito = $this->crearOrdenCompra($ordenCompra);
					return $this->renderAjax('openPayCreditCard', [
						"ordenCompra"=>$ordenCompraTarjetaCredito,
						"description" =>  $ordenCompraTarjetaCredito->txt_description,
						"orderId" => $ordenCompraTarjetaCredito->txt_order_number,
						"amount" => $ordenCompraTarjetaCredito->num_total,]);
				}else{
					$ordenCompra = $this->crearOrdenCompra($ordenCompra);
					$charger =  $this->generarOrdenCompraOpenPay($ordenCompra->txt_description, $ordenCompra->txt_order_number, $ordenCompra->num_total);
				}
				

                
                return $this->renderAjax('openPay', ['charger'=>$charger, 'ordenCompra'=>$ordenCompra]);
            break;

        }
    }

    public function crearOrdenCompra($ordenCompraGuardada){
        
           $ordenNumber = Utils::generateToken('oc_');
           

           $ordenCompra = new EntOrdenesCompras();
           $ordenCompra->txt_order_number = $ordenNumber;
           $ordenCompra->txt_description = $ordenCompraGuardada->txt_description;
           $ordenCompra->id_usuario = $ordenCompraGuardada->id_usuario;
           $ordenCompra->id_tipo_pago = $ordenCompraGuardada->id_tipo_pago;
           $ordenCompra->fch_creacion = Utils::getFechaActual();
           $ordenCompra->b_pagado = 0;
           $ordenCompra->num_total = $ordenCompraGuardada->num_total;
		   $ordenCompra->b_habilitado = 1;
		   $ordenCompra->id_plan = $ordenCompraGuardada->id_plan;
		   $ordenCompra->b_subscripcion = $ordenCompraGuardada->b_subscripcion;
           $ordenCompra->save();
          return  $ordenCompra;
   }

    

    public function actionSeleccionarProducto(){
       $productos = Products::find()->where(['b_enabled'=>1])->orderBy('num_order')->all();


        return $this->render('formaPago', ['productos'=>$productos]);
    }

    private function getProductoByToken($token){
        $producto = Products::find()->where(['txt_product_number'=>$token])->one();

        if($producto){
            return $producto;
        }else{
            throw new NotFoundHttpException ( 'The requested page does not exist.' );
        }
        
    }

    private function getFormaPagoByToken($token){
         $formaPago = CatTiposPago::find()->where(['txt_token'=>$token])->one();

        if($formaPago){
            return $formaPago;
        }else{
            throw new NotFoundHttpException ( 'The requested page does not exist.' );
        }
    }
    
    private function generarOrdenCompraOpenPay($description='Descripción del producto', $orderNumber=null, $monto ){
    	$pago = new Pagos();
    	return $pago->oPCodeBar($description, $orderNumber, $monto);
    }


    public function actionPagarTarjetaOpenPay(){

            $pago = new Pagos();

            if (isset ( $_POST ["token_id"] ) && $_POST ["orderId"] && $_POST['deviceIdHiddenFieldName']) {
			
            $ordenCompra = EntOrdenesCompras::find()->where(['txt_order_number'=>$_POST['orderId']])->one();
	
			if (empty ( $ordenCompra )) {
				throw new BadRequestHttpException ( 400, 'Datos requeridos.' );
			}
			
			try {
				$charge = $pago
                    ->createChargeCreditCard ( $ordenCompra->txt_description, $ordenCompra->txt_order_number, $ordenCompra->num_total, $_POST ["token_id"], $_POST['deviceIdHiddenFieldName']);
				
				echo "success";

			
			} catch ( \OpenpayApiTransactionError $e ) {
				echo $this->parseMessage($e->getMessage ());
			}catch(\OpenpayApiRequestError $e){
				echo $this->parseMessage($e->getMessage ());
			}catch(\ErrorException $e){
				echo $this->parseMessage($e->getMessage ());
			}catch(\Exception $e){
				echo $this->parseMessage($e->getMessage ());
			}catch(\OpenpayApiError $e){
				echo $this->parseMessage($e->getMessage ());
			}
			
			exit;
		}
	}
	
	public function actionCrearSubscripcion(){

		$pago = new Pagos();

		if (isset ( $_POST ["token_id"] ) && $_POST ["orderId"] && $_POST['deviceIdHiddenFieldName']) {
		
		$ordenCompra = EntOrdenesCompras::find()->where(['txt_order_number'=>$_POST['orderId']])->one();
		$plan = $ordenCompra->idPlan;

		if (empty ( $ordenCompra )) {
			throw new BadRequestHttpException ( 400, 'Datos requeridos.' );
		}
		
		try {
			$charge = $pago
				->addTarjetaCliente ( $ordenCompra->txt_description, $ordenCompra->txt_order_number, $ordenCompra->num_total, $_POST ["token_id"], $_POST['deviceIdHiddenFieldName'], $plan->txt_plan_open_pay);
			
			echo "success";
		} catch ( \OpenpayApiTransactionError $e ) {
			echo $this->parseMessage($e->getMessage ());
		}catch(\OpenpayApiRequestError $e){
			echo $this->parseMessage($e->getMessage ());
		}catch(\ErrorException $e){
			echo $this->parseMessage($e->getMessage ());
		}catch(\Exception $e){
			echo $this->parseMessage($e->getMessage ());
		}catch(\OpenpayApiError $e){
			echo $this->parseMessage($e->getMessage ());
		}
		
		
		exit;
		
	}
}

	private function parseMessage($msg){
		$pos = strrpos($msg, "Card declined (k)");
		if ($pos === true) { // nota: tres signos de igual
			// encontrado.
			return "La tarjeta fue declinada, por sistema antifraude";
		}

		$pos = strrpos($msg, "Card declined (o)");
		if ($pos === true) { // nota: tres signos de igual
			// encontrado.
			return "La tarjeta fue declinada, por sistema antifraude";
		}

		$pos = strrpos($msg, "Card declined");
		if ($pos === true) { // nota: tres signos de igual
			// encontrado.
			return "La tarjeta fue declinada, favor de validar con su banco";
		}


		return $msg;
	}

    /**
	 * Open pay hara el registro del pago en este action
	 */
	public function actionOpWebHook() {
		$entityBody = file_get_contents ( 'php://input' );
		$json = json_decode ( $entityBody, true );
		
        $this->crearLog('Open Pay', '------------- RECEPCION DE WEBHOOK -------------------');
        $this->crearLog('Open Pay','type: ' . $json ['type'] );
		$this->crearLog('Open Pay',"event_date: " . $json ['event_date']);
		
		
		switch ($json ['type']) {
			case "verification" :
                $this->crearLog('Open Pay',"Codigo de verificacion: " . $json ['verification_code']);
                $this->crearLog('Open Pay',"Id de peticion: " . $json ["id"]);
			
				break;
			
			case "charge.succeeded" :
			 	$this->crearLog('Open Pay',"Inicio de proceso de pago");
				$this->processPaymentOP ( $json, $entityBody );
				break;
		}
	}

    // $userCreditos = new EntUsuariosCreditos();
	// 		$userCreditos->agregarCreditos($comentario->id_usuario, $contestar->costo);

    /**
	 * Guarda registro del pago
	 *
	 * @param unknown $json        	
	 * @param unknown $data        	
	 */
	private function processPaymentOP($json, $data) {
		$transaction = $json ['transaction'];
		
		$txn_id = $transaction ['id'];
		$payment_amount = $transaction ['amount'];
		$payment_currency = "NOT DEFINED";
		$payment_status = $transaction ['status'];
		$quantity = 1;
		$mc_gross = $transaction ['amount'];
		$order_id = $transaction ['order_id'];
		
		$ordenCompra = EntOrdenesCompras::find()->where(['txt_order_number'=>$order_id,'b_pagado'=>0])->one();
		
		if(empty($ordenCompra)){

			
			if(!$transaction['order_id'] && ($transaction['method'] == "card")){

				$tarjeta = EntTarjetas::find()->where(["txt_tarjeta"=>$transaction['card']["id"]])->one();

				$pagoRecibido = new EntPagosRecibidos ();
				$pagoRecibido->id_usuario = $tarjeta->id_usuario;
				$pagoRecibido->id_tipo_pago = 2;
				$pagoRecibido->txt_transaccion_local = 'Local';
				$pagoRecibido->txt_notas = 'Notas';
				$pagoRecibido->txt_estatus = $payment_status;
				$pagoRecibido->txt_transaccion = $txn_id;
				$pagoRecibido->txt_cadena_comprador = $data;
				$pagoRecibido->txt_monto_pago = $mc_gross;
				$pagoRecibido->id_orden_compra = null;
				$pagoRecibido->save();

				$this->crearLog('Open Pay', '------------- Envio de email -------------------');
				$usuario = EntUsuarios::find()->where(['id_usuario'=>$tarjeta->id_usuario])->one();
				$utils = new \app\modules\ModUsuarios\models\Utils();
				$parametrosEmail = [
						'nombre' => $usuario->nombreCompleto,
						'tipo'=>"donativo recurrente",
						'transaccion'=>$txn_id,
						'totalPagado'=>$mc_gross
				];
			
				$utils->sendPagoNotificacion($usuario->txt_email, $parametrosEmail );

				return;

				
			}else{

				$this->crearLog ('OpenPayError', "El order ID no existe o ya esta marcado como completo :" . $order_id );
				return;
			}
		}
			// Carga la orden
		$item_number = $ordenCompra->txt_order_number;
		$custom = $ordenCompra->id_usuario;
		$item_name = $ordenCompra->txt_description;
		
		
		$this->crearLog ('OpenPayUser'.$ordenCompra->id_usuario, "------------- PAGO RECIBIDO de transacción :$txn_id -----------\n\r" );
		
		// Solo genera el log cambiar al de yii
		if (true) {
			
			$this->crearLog ('OpenPayUser'.$ordenCompra->id_usuario, 
                                "Item name:" . $item_name . "\n\r" . 
                                "Item number :" . $item_number . "\n\r" . 
                                "quantity :" . $quantity . "\n\r" . 
                                "Payment Status :" . $payment_status . "\n\r" . 
                                "Payment amount :" . $payment_amount . "\n\r" . 
                                "Txn Id :" . $txn_id . "\n\r" . 
                                "custom :" . $custom . "\n\r" . 
                                "mc gross :" . $mc_gross . "\n\r" );
		}
		
		// VALIDA QUE LA TRANSACCION NO SE ENCUIENTRE REGISTRADA EN LA BASE DE DATOS PREVIAMENTE
        $pagoRecibed = EntPagosRecibidos::find()->where(['txt_transaccion'=>$txn_id])->one();

		
		if (! empty ( $pagoRecibed )) {
			$this->crearLog ('OpenPayUser'.$ordenCompra->id_usuario, "TRANSACCION REPETIDA: $txn_id \n\r" );
			return;
		}
		
		// Verifica el precio vs el producto
		if (( double ) $ordenCompra->num_total != ( double ) $mc_gross) {
			$this->crearLog ('OpenPayUser'.$ordenCompra->id_usuario, "PRODUCTO Y MONTO INCORRECTO: id_product=$item_number AND num_price=$mc_gross\n\r" );
			return;
		}
		
		// Verifica que la cantidad de productos adquiridos sean 1
		if ($quantity != 1) {
			$this->crearLog ('OpenPayUser'.$ordenCompra->id_usuario, "CANTIDAD DE PRODUCTOS INCORRECTO: quantity=$quantity\n\r" );
		}
		
		$pagoRecibido = new EntPagosRecibidos ();
		$pagoRecibido->id_usuario = $ordenCompra->id_usuario;
		$pagoRecibido->id_tipo_pago = 2;
		$pagoRecibido->txt_transaccion_local = 'Local';
		$pagoRecibido->txt_notas = 'Notas';
		$pagoRecibido->txt_estatus = $payment_status;
		$pagoRecibido->txt_transaccion = $txn_id;
		$pagoRecibido->txt_cadena_comprador = $data;
		$pagoRecibido->txt_monto_pago = $mc_gross;
		$pagoRecibido->id_orden_compra = $ordenCompra->id_orden_compra;
		
		

		$transaction = Yii::$app->db->beginTransaction();
		$error = false;
		try {
			if ($pagoRecibido->save ()) {

				$ordenCompra->b_pagado = 1;
				if($ordenCompra->save()){
					$usuario = EntUsuarios::find()->where(['id_usuario'=>$ordenCompra->id_usuario])->one();

					$utils = new \app\modules\ModUsuarios\models\Utils();
					$parametrosEmail = [
							'nombre' => $usuario->txt_username,
							'tipo'=>"donativo único",
							'transaccion'=>$ordenCompra->txt_order_number,
							'totalPagado'=>$mc_gross
					];
				
					$utils->sendPagoNotificacion($usuario->txt_email, $parametrosEmail );

				}else{
					$error = true;
					$this->crearLog('OpenPayUser'.$ordenCompra->id_usuario, 'No se puedo actualizar la orden de compra'. json_encode ( $ordenCompra->errors) );
				}
				
			} else {
				$error = true;
				$this->crearLog ('OpenPayUser'.$ordenCompra->id_usuario, "Error al guardar el pago " . json_encode ( $pagoRecibido->errors) );
			}
			if ($error) {
				$transaction->rollback ();
				return;
			} else {
				$transaction->commit ();
			}
		} catch ( ErrorException $e ) {
			$this->crearLog ('OpenPayUser'.$ordenCompra->id_usuario, "Ocurrio un problema al guardar la información=print_r($e)\n\r" );
			$transaction->rollback ();
		}
		$this->crearLog ('OpenPayUser'.$ordenCompra->id_usuario, "------------------- Pago correcto---------------------\n\r" );

		
		
		
	}
    

    public function crearLog($nombreArchivo,$message){
        
        $basePath = Yii::getAlias('@app'); 
        $fichero = $basePath.'/logsPagos/'.$nombreArchivo.'.log';

        $persona =  Utils::getFechaActual()."\n".$message."\n\n";
        
        $fp = fopen($fichero,"a");
        fwrite($fp,$persona);
        fclose($fp);
    }
	
	public function actionCrearPlanes(){
		$pago = new Pagos();
		$pago->generarPlan();
	}

	public function actionBorrarPlan($id=null){
		$pago = new Pagos();
		$pago->deletePlan($id);
	}

	public function actionBorrarCliente($id=null){
		$pago = new Pagos();
		$pago->borrarCliente($id);
	}

	public function actionBorrarTarjetaCliente($id=null, $idt){
		$pago = new Pagos();
		$pago->borrarTarjeta($id, $idt );
	}

	public function actionBorrarSubscripcionCliente($ids=null){
		Yii::$app->response->format = Response::FORMAT_JSON;
		$respuesta["status"] = "error";
		$usuario = Yii::$app->user->identity;
		$subscripcionHabilitada = EntSubscripciones::find()->where(["id_usuario"=>$usuario->id_usuario, "txt_subscipcion_open_pay"=>$ids, "b_subscrito"=>1])->one();
		if($subscripcionHabilitada){
			
			$pago = new Pagos();
			if($pago->borrarSubscripcion($usuario->txt_usuario_open_pay, $ids)){
				$subscripcionHabilitada->b_subscrito = 0;
				$subscripcionHabilitada->fch_suspencion = Utils::getFechaActual();
				$subscripcionHabilitada->txt_motivo = $_POST["EntSubscripciones"]["txt_motivo"];

				$parametrosEmail = [
					'nombre' => $usuario->nombreCompleto,
					'contacto'=>$usuario->txt_email,
					'motivo'=>$subscripcionHabilitada->txt_motivo,
				];

				if($subscripcionHabilitada->save()){

					$utils = new \app\modules\ModUsuarios\models\Utils();
					$utils->sendNotificacionSuspencion($usuario->txt_email, $parametrosEmail);

					$respuesta["status"] = "success";
					return $respuesta;
				}
			}else{
				$respuesta["status"] = "error";
					return $respuesta;
			}
		}
		
	}

	public function actionSendEmailTest(){
		$utils = new \app\modules\ModUsuarios\models\Utils();
				$parametrosEmail = [
						'nombre' => "Humberto Antonio",
						'transaccion'=>"12345",
						'totalPagado'=>"140"
				];
			
				$utils->sendPagoNotificacion("cloudelric74@gmail.com", $parametrosEmail );
	}

	public function actionGenerarFactura(){
		Yii::$app->response->format = Response::FORMAT_JSON;
		$respuesta["status"] = "error";
		$respuesta["message"] = "Ocurrio un problema";
		$botones = "";

		if(isset($_POST["t"])){
			$transaccion = $_POST["t"];
			$ordenPagada = EntPagosRecibidos::find()->where(["txt_transaccion"=>$transaccion])->one();
			$botones = '<a class="btn donaciones-facturar-pdf js-descargar-pdf" target="_blank" href='.Url::base().'/pagos/descargar-factura-pdf?token='.$transaccion.'>PDF</a> 
			<a href='.Url::base().'/pagos/descargar-factura-xml?token='.$transaccion.' target="_blank" class="btn donaciones-facturar-xml js-descargar-xml">XML</a>';
		}

		if(!$ordenPagada){
			$respuesta["message"] = "No existe la transaccion";
			return $respuesta;
		}

		if($ordenPagada->b_facturado){
			$respuesta["status"] = "success";
			$respuesta["botones"] =  $botones;
			return $respuesta;
		}
		
		// Datos de facturación
		$usuario = Yii::$app->user->identity;
		$facturacion = EntDatosFacturacion::find()->where(["id_usuairo"=>$usuario->id_usuario])->one();
		if(!$facturacion){
			$facturacion = new EntDatosFacturacion();
		}

		$facturacion->id_usuairo = $usuario->id_usuario;
		
		if($facturacion->load(Yii::$app->request->post()) && $facturacion->save() && isset($_POST["t"])){
			
			$factura = new Pagos();
			$facturaGenerar = $factura->generarFactura($facturacion, $ordenPagada);
			
			if(isset($facturaGenerar->pdf) && isset($facturaGenerar->xml)){
				
				$this->validarDirectorio("facturas/".$usuario->txt_token);
				$this->validarDirectorio("facturas/".$usuario->txt_token."/".$transaccion);

				$pdf = base64_decode($facturaGenerar->pdf);

				$xml = base64_decode($facturaGenerar->xml);

				file_put_contents("facturas/".$usuario->txt_token."/".$transaccion."/factura.pdf", $pdf);
				file_put_contents("facturas/".$usuario->txt_token."/".$transaccion."/factura.xml", $xml);
				//EICA821101RM6
				$respuesta["status"] = "success";
				$respuesta["message"] = "Recibo generado";
				$respuesta["botones"] =  $botones;

				$ordenPagada->b_facturado = 1;
				$ordenPagada->save();
				return $respuesta;
			}

			if(isset($facturaGenerar->error) && $facturaGenerar->error){
				$respuesta["message"] = $facturaGenerar->message;
				return $respuesta;
			}

		}else{
			 print_r($facturacion->errors);
			print_r($_POST);
			exit;
		}

		return $respuesta;
	}

	public function actionTestDirectorio(){
		$usuario = Yii::$app->user->identity;
		$this->validarDirectorio($usuario->txt_token);
	}

	public function validarDirectorio($path){
		
		if (!file_exists($path)) {
			mkdir($path, 0777);
		}
	}

	public function actionDescargarFacturaPdf($token=null){
		$usuario = Yii::$app->user->identity;
		$ordenPagada = EntPagosRecibidos::find()->where(["txt_transaccion"=>$token])->one();
		$file = "facturas/".$usuario->txt_token."/".$ordenPagada->txt_transaccion."/factura.pdf";

		if (file_exists($file)) {
			
			return Yii::$app->response->sendFile($file);
		}
	}

	public function actionDescargarFacturaXml($token=null){

		$usuario = Yii::$app->user->identity;
		$ordenPagada = EntPagosRecibidos::find()->where(["txt_transaccion"=>$token])->one();
		$file = "facturas/".$usuario->txt_token."/".$ordenPagada->txt_transaccion."/factura.xml";

		if (file_exists($file)) {

			return Yii::$app->response->sendFile($file);
		}

		
	}

	public function actionTestB($idCustomer, $idSubscripcion){
		$pago = new Pagos();
		$pago->borrarSubscripcion($usuario->txt_usuario_open_pay, $ids);
	}
}
